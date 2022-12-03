<?php

require '../vendor/autoload.php';
use Shaarli\NetscapeBookmarkParser\NetscapeBookmarkParser;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$DOTENVDATA=$dotenv->load();




//Remove the previously uploaded file and folder
if(file_exists('../uploads')){
    foreach(scandir('../uploads') as $dir){
        
    unlink('../uploads/'.$dir);
    }
    rmdir('../uploads');
}
if(!file_exists('../uploads')){
    mkdir('../uploads');
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
            header('Location: ../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=file_not_sent');
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
        header('Location: ../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=filesize_exceeded');
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
        header('Location: ../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=wrong_file_format');
        exit();
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    if (!move_uploaded_file(
        $_FILES['bookmarks_file']['tmp_name'],
        sprintf('../uploads/%s.%s',
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







$filenames=scandir('../uploads/');
$filename=$filenames[2];

$parser = new NetscapeBookmarkParser();
$bookmarks_panel_items = $parser->parseFile('../uploads/'.$filename);
//var_dump($bookmarks);
//exit();
//session_start();
//$_SESSION["bookmarks"]=$bookmarks;


try{
    require_once('./connect_database.php');

    //echo "<table>";
    foreach($bookmarks_panel_items as $bookmark_key=>$bookmark_value){
        
        //order when printing highest level item values: bookmark, image header, url, tags (as an array), description, creation date, publicity (possible values atleast public)
        foreach($bookmark_value as $bmv_key=>$bmv_value){
            
            
            //search for url, folder structure of each bookmark is described with the tags array
                if($bmv_key =="url"){

                    $check_if_bookmark_exists_query=$connection->prepare("SELECT COUNT(url) FROM bookmark WHERE url=?");
                    $check_if_bookmark_exists_query->bind_param("s",$bmv_value);
                    
                    if($check_if_bookmark_exists_query->execute()){

                        $check_if_bookmark_exists_query->store_result();
                        $check_if_bookmark_exists_query->bind_result($bookmark_count);
                        $check_if_bookmark_exists_query->fetch();
                        if($bookmark_count == 0){
                            $current_url=$bmv_value;
                            $current_name=$bookmark_value["name"];
                            if($bookmark_value["description"]==null){
                                $current_description="";
                            }
                            else{
                                $current_description=$bookmark_value["description"];
                            }
                            $current_creation_date=$bookmark_value["dateCreated"];
                            $save_bookmark_to_database_query=$connection->prepare("INSERT INTO bookmark(url, name, description, creation_date) VALUES (?,?,?,?)");
                            $save_bookmark_to_database_query->bind_param("ssss",$current_url,$current_name,$current_description,$current_creation_date);
                            
                            if($save_bookmark_to_database_query->execute()){

                                $current_tags=implode(" ",$bookmark_value["tags"]);
                                echo "<br>".$current_tags."<br>";
                                //check if the bookmark folder hierarchy already exists in the database
                                $check_if_tags_already_exist_query=$connection->prepare("SELECT COUNT(tags) FROM tagsofbookmarks WHERE tags=?");
                                $check_if_tags_already_exist_query->bind_param("s",$current_tags);
                                
                                if($check_if_tags_already_exist_query->execute()){

                                    $check_if_tags_already_exist_query->store_result();
                                    $check_if_tags_already_exist_query->bind_result($tags_count);
                                    $check_if_tags_already_exist_query->fetch();
                                    
                                    if($tags_count == 0){
                                        $save_tags_to_database_query=$connection->prepare("INSERT INTO tagsofbookmarks(tags) VALUES (?)");
                                        $save_tags_to_database_query->bind_param("s",$current_tags);
                                        
                                        if($save_tags_to_database_query->execute()){

                                            $save_user_with_this_bookmark_and_these_tags_query=$connection->prepare("INSERT INTO bookmarksofusers(username, url, tags_id) VALUES(?,?,(SELECT MAX(tags_id) FROM tagsofbookmarks))");
                                            $save_user_with_this_bookmark_and_these_tags_query->bind_param("ss", $_SESSION["username"], $current_url);
                                            
                                            if($save_user_with_this_bookmark_and_these_tags_query->execute()){
                                                
                                                    //Simply continue to the next url after success
                                                    continue;
                                            }

                                        }
                                        else{
                                            header('Location: ../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                                            exit();
                                        }

                                    }
                                    else{
                                        //If the folder hierarchy already exists, simply appoint the current user for the bookmark that has the specific folder hierarchy location
                                        $retrieve_tags_id_query=$connection->prepare("SELECT tags_id FROM tagsofbookmarks WHERE tags_id= (SELECT tags_id FROM bookmarksofusers WHERE username= (SELECT username FROM userprofile WHERE username=?))");
                                        $retrieve_tags_id_query->bind_param("s",$_SESSION["username"]);
                                        if($retrieve_tags_id_query->execute()){

                                            $retrieve_tags_id_query->store_result();
                                            $retrieve_tags_id_query->bind_result($tags_id);
                                            echo $current_url." ".$current_name." ".$current_description." ".$tags_id."<br>";

                                                
                                            $save_user_with_this_bookmark_and_these_tags_query=$connection->prepare("INSERT INTO bookmarksofusers(username, url, tags_id) VALUES(?,?,?)");
                                            $save_user_with_this_bookmark_and_these_tags_query->bind_param("ssi", $_SESSION["username"], $current_url, $tags_id);
                                            
                                            if($save_user_with_this_bookmark_and_these_tags_query->execute()){
                                                //Simply continue to the next url after success
                                                continue;
                                            }
                                            $retrieve_tags_id_query->free_result();
                                        }
                                        else{
                                            header('Location: ../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                                            exit();
                                        }
                                        
                                    }

                                    $check_if_tags_already_exist_query->free_result();
                                }
                                else{
                                    header('Location: ../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                                    exit();
                                }
                            
                            }
                            else{
                                header('Location: ../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                                exit();
                            }
                            
                        }
                        else{
                            //If an identical url already exists in the database, continue checking for the next bookmark's name
                            continue;
                        }
                    
                        $check_if_bookmark_exists_query->free_result();
                    
                    }else{
                        header('Location: ../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
                        exit();
                    }
                
                }
            
            
            

            
        }
        
    }
    exit();

    //Redirect with success if no error redirection
    header('Location: ../index.php?page=bookmarks_page&bookmarks_file_upload_status=yes');
    exit();

    



}catch(Exception $e){
    //Database error
    echo $e;
    exit();
    header('Location: ../index.php?page=bookmarks_page&bookmarks_file_upload_status=no&error=database_error');
}





?>