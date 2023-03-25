<?php

require '../../vendor/autoload.php';
use Shaarli\NetscapeBookmarkParser\NetscapeBookmarkParser;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
$DOTENVDATA=$dotenv->load();




//Remove the previously uploaded file and folder
if(file_exists('../../uploads')){
    foreach(scandir('../../uploads') as $dir){
        
    unlink('../../uploads/'.$dir);
    }
    rmdir('../../uploads');
}
if(!file_exists('../../uploads')){
    mkdir('../../uploads');
}




// https://www.php.net/manual/en/features.file-upload.php#114004
header('Content-Type: text/plain; charset=utf-8');

try {
   
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['bookmarks_file']['error']) ||
        is_array($_FILES['bookmarks_file']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['upfile']['error'] value.
    switch ($_FILES['bookmarks_file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            //throw new RuntimeException('No file sent.');
            header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=file_not_sent');
            exit();
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here.
    if ($_FILES['bookmarks_file']['size'] > 1000000) {
        //throw new RuntimeException('Exceeded filesize limit.');
        header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=filesize_exceeded');
        exit();
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['bookmarks_file']['tmp_name']),
        array(
            'html' => 'text/html'
        ),
        true
    )) {
        //throw new RuntimeException('Invalid file format.');
        header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=wrong_file_format');
        exit();
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    if (!move_uploaded_file(
        $_FILES['bookmarks_file']['tmp_name'],
        sprintf('../../uploads/%s.%s',
            sha1_file($_FILES['bookmarks_file']['tmp_name']),
            $ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    echo 'File is uploaded successfully.';

} catch (RuntimeException $e) {

    echo $e->getMessage();

}







$filenames=scandir('../../uploads/');
$filename=$filenames[2];

$parser = new NetscapeBookmarkParser();
$bookmarks_panel_items = $parser->parseFile('../../uploads/'.$filename);



try{
    require_once('../connect_database.php');

    $some_folder_paths_are_too_long=false;
    //$maximum_folder_path_length=0;
    //$cached_path_length=null;
    $successfully_loaded_bookmark_count=0;

    foreach($bookmarks_panel_items as $bookmark_key=>$bookmark_value){
        //if($cached_path_length > $maximum_folder_path_length){
        //    $maximum_folder_path_length=$cached_path_length;
        //}
        //First check that the folder path (tags) is not too long for any bookmark, needed for folder generation in UI (generate_bookmark_folders)
        if(count($bookmark_value["tags"])>10){
            $some_folder_paths_are_too_long=true;
            

        }
        //$cached_path_length=$maximum_folder_path=count($bookmark_value["tags"]);
    }

    //echo "longest folder path in imported file:".$maximum_folder_path_length;

 
    if($some_folder_paths_are_too_long == false){
        foreach($bookmarks_panel_items as $bookmark_key=>$bookmark_value){
        
            

            //order when printing highest level item values: bookmark, image header, url, tags (as an array), description, creation date, publicity (possible values atleast public)
            foreach($bookmark_value as $bmv_key=>$bmv_value){
                    //echo "\nbmv_value:";
                    //print_r($bmv_value);
                
                    //search for url, folder structure of each bookmark is described with the tags array
                    if($bmv_key =="url"){

                        $current_url=$bmv_value;
                        $current_name=$bookmark_value["name"];
                        if($bookmark_value["description"]==null){
                            $current_description="";
                        }
                        else{
                            $current_description=$bookmark_value["description"];
                        }
                        $current_creation_date=$bookmark_value["dateCreated"];
                        $current_tags=implode(" ",$bookmark_value["tags"]);
                        

                        //SELECT COUNT(bookmark.url) FROM bookmark INNER JOIN bookmarksofusers ON bookmark.url=bookmarksofusers.url AND username=? INNER JOIN tagsofbookmarks ON tagsofbookmarks.tags_id=bookmarksofusers.tags_id WHERE bookmark.url=?)";
                        $check_if_bookmark_exists_query=$connection->prepare("SELECT COUNT(url) FROM bookmark WHERE url=?");
                        $check_if_bookmark_exists_query->bind_param("s",$bmv_value);
                        
                        if($check_if_bookmark_exists_query->execute()){

                            $check_if_bookmark_exists_query->store_result();
                            $check_if_bookmark_exists_query->bind_result($bookmark_count);
                            $check_if_bookmark_exists_query->fetch();
                            
                            if($bookmark_count == 0){
                                //$current_url=$bmv_value;
                                //$current_name=$bookmark_value["name"];
                                //if($bookmark_value["description"]==null){
                                //    $current_description="";
                                //}
                                //else{
                                //    $current_description=$bookmark_value["description"];
                                //}
                                //$current_creation_date=$bookmark_value["dateCreated"];

                                //echo "\n\nBOOKMARK COUNT 0";
                                $save_bookmark_to_database_query=$connection->prepare("INSERT INTO bookmark(url, name, description, creation_date) VALUES (?,?,?,?)");
                                $save_bookmark_to_database_query->bind_param("ssss",$current_url,$current_name,$current_description,$current_creation_date);
                                
                                if($save_bookmark_to_database_query->execute()){
                                    //echo "\nSAVING BOOKMARK TO DATABASE SUCCEEDED";
                                    $save_bookmark_to_database_query->store_result();
                                    //remember to create a special dummy invisible bookmark for preserving empty folders if it doesn't already exist in database
                                    $dummy_bookmark_for_preserving_empty_folders="DUMMY_BOOKMARK_FOR_PRESERVING_EMPTY_FOLDERS";
                                    $insert_dummy_bookmark_query=$connection->prepare("INSERT IGNORE INTO bookmark (url,name, description, creation_date) VALUES (?,?,?,?)");
                                    $insert_dummy_bookmark_query->bind_param("ssss", $dummy_bookmark_for_preserving_empty_folders,$dummy_bookmark_for_preserving_empty_folders,$dummy_bookmark_for_preserving_empty_folders,$dummy_bookmark_for_preserving_empty_folders);
                                    if($insert_dummy_bookmark_query->execute()){
                                        //echo "\nINSERTING DUMMY BOOKMARK SUCCEEDED";
                                        $insert_dummy_bookmark_query->store_result();
                                        require_once('create_tags_and_set_tags_for_user.php');
                                        $create_tags_and_set_tags_for_user_status=create_tags_and_set_tags_for_user($connection, $current_tags);
                                        if($create_tags_and_set_tags_for_user_status==true){
                                            //echo "\nCREATE TAGS AND SET TAGS FOR USER STATUS: TRUE";
                                            require_once('set_user_for_the_bookmark.php');
                                            $set_bookmark_user_status=set_user_for_the_bookmark($connection, $current_url, $current_tags);
                                            if($set_bookmark_user_status == true){
                                                //echo "\nSET USER STATUS FOR THE BOOKMARK: TRUE\n";
                                                $successfully_loaded_bookmark_count+=1;
                                                continue;
                                                //exit();
                                            }
                                            else{
                                                //echo "\nSET USER STATUS FOR THE BOOKMARK: FALSE";
                                                //exit();
                                                header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                                                exit();   
                                            }
                                        }
                                        else{
                                            //echo "\nCREATE TAGS AND SET TAGS FOR USER STATUS: FALSE";
                                            //exit();
                                            header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                                            exit();
                                        }
                                        $insert_dummy_bookmark_query->free_result();
                                    }
                                    $save_bookmark_to_database_query->free_result();             
                                }
                                else{
                                    //echo "\nSAVING BOOKMARK TO DATABASE FAILED";
                                    //exit();
                                    header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                                    exit();
                                }
                                
                            }
                            else{
                                //echo "\nBOOKMARK COUNT 1 AND OVER";
                                //If an identical bookmark (url) already exists in the database, check and define (if needed) the folder-bookmark combination for the user regardless
                                //remember to create a special dummy invisible bookmark for preserving empty folders if it doesn't already exist in database
                                $dummy_bookmark_for_preserving_empty_folders="DUMMY_BOOKMARK_FOR_PRESERVING_EMPTY_FOLDERS";
                                $insert_dummy_bookmark_query=$connection->prepare("INSERT IGNORE INTO bookmark (url,name, description, creation_date) VALUES (?,?,?,?)");
                                $insert_dummy_bookmark_query->bind_param("ssss", $dummy_bookmark_for_preserving_empty_folders,$dummy_bookmark_for_preserving_empty_folders,$dummy_bookmark_for_preserving_empty_folders,$dummy_bookmark_for_preserving_empty_folders);
                                if($insert_dummy_bookmark_query->execute()){
                                    //echo "\nINSERTING DUMMY BOOKMARK SUCCEEDED;";
                                    $insert_dummy_bookmark_query->store_result();
                                    require_once('create_tags_and_set_tags_for_user.php');
                                    $create_tags_and_set_tags_for_user_status=create_tags_and_set_tags_for_user($connection, $current_tags);
                                    if($create_tags_and_set_tags_for_user_status == true){
                                        //echo "\nCREATE TAGS AND SET TAGS FOR USER STATUS: TRUE";
                                        require_once('set_user_for_the_bookmark.php');
                                    
                                        $set_bookmark_user_status=set_user_for_the_bookmark($connection, $current_url, $current_tags);
                                        if($set_bookmark_user_status == true){
                                            //echo "\nSET USER STATUS FOR THE BOOKMARK: TRUE";
                                            $successfully_loaded_bookmark_count+=1;
                                            continue;
                                            //exit();
                                        }
                                        else{
                                            //echo "\nSET USER STATUS FOR THE BOOKMARK: FALSE";
                                            //exit();
                                            header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                                            exit();   
                                        
                                        }
                                    }
                                    else{
                                        //echo "\nCREATE TAGS AND SET TAGS FOR USER STATUS: FALSE";
                                        //exit();
                                        header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                                        exit();
                                    }
                                    $insert_dummy_bookmark_query->free_result();
                                }
                                else{
                                    //echo "\nINSERTING DUMMY BOOKMARK FAILED";
                                    //exit();
                                    header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                                    exit();
                                }
                                
                            }
                        
                            $check_if_bookmark_exists_query->free_result();
                        
                        }else{
                            header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                            exit();
                        }
                    
                    }
                
                
            

                
            }

        
        }


            
    }
    else{
        header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=some_folder_paths_too_long');
        exit();
    }
    //exit();
    //echo "\nIMPORTING BOOKMARKS SUCCEEDED";
    $_SESSION["successfully_loaded_bookmark_count"]=$successfully_loaded_bookmark_count;
    //Redirect with success if no error redirection
    header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=yes');
    exit();

    



}catch(Exception $e){
    //Database error
    //echo $e;
    //exit();
    header('Location: ../../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
    exit();
}





?>