<?php


if(isset($_POST["delete_current_bookmark_folder_structure_from_top"]) && isset($_POST["delete_bookmark_folder_name"])){

        echo "deleting the folder: ".$_POST["delete_current_bookmark_folder_structure_from_top"];
        
    try{

        require dirname(dirname((__DIR__))).'/vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
        $DOTENVDATA=$dotenv->load();

        require_once('../connect_database.php');
        $bookmark_folder_from_post=$_POST["delete_current_bookmark_folder_structure_from_top"];
        $subfolders_match=$_POST["delete_current_bookmark_folder_structure_from_top"]."%";
        
        //first retrieve all folders and subfolders regardless of who uses them
        
        $retrieve_all_subfolders_inside_the_folder_query=$connection->prepare("SELECT tags FROM tagsofbookmarks WHERE tags LIKE ? OR tags=?");
        $retrieve_all_subfolders_inside_the_folder_query->bind_param("ss",$subfolders_match, $bookmark_folder_from_post);
        if($retrieve_all_subfolders_inside_the_folder_query->execute()){
            $retrieve_all_subfolders_inside_the_folder_query->store_result();
            $retrieve_all_subfolders_inside_the_folder_query->bind_result($all_subfolders_inside_the_folder);
            
            //Then check if other users exist for any of the subfolders, if more than 1 user in any case, delete only the relevant bookmark references (bookmarksofusers), so that
            //the associated folder cannot be rendered for the current user
            $leave_folders_alone=false;

            $all_subfolders_as_an_array=array();
            $_SESSION["successfully_deleted_total_bookmark_count"]=0;
            while($retrieve_all_subfolders_inside_the_folder_query->fetch()){
                array_push($all_subfolders_as_an_array, $all_subfolders_inside_the_folder);
            }
            echo "<br>all subfolders as an array for later use:";
            foreach($all_subfolders_as_an_array as $array_subfolder_key=>$array_subfolder_value){
                echo"<br>".$array_subfolder_value;
            }
            echo "<br><br>";

            while($retrieve_all_subfolders_inside_the_folder_query->fetch()){
            
                
                    echo "<br>".$all_subfolders_inside_the_folder;
                
                
                
                //foreach($all_subfolders_inside_the_folder as $subfolder_key=>$subfolder_name){
                    

                    
                    $check_if_subfolder_only_used_by_current_user_query=$connection->prepare("SELECT DISTINCT username FROM bookmarksofusers INNER JOIN tagsofbookmarks ON bookmarksofusers.tags_id=(SELECT tags_id FROM tagsofbookmarks WHERE tags =?)");
                    $check_if_subfolder_only_used_by_current_user_query->bind_param("s",$subfolder_name);
                    
                    if($check_if_subfolder_only_used_by_current_user_query->execute()){

                        $check_if_subfolder_only_used_by_current_user_query->store_result();
                        $check_if_subfolder_only_used_by_current_user_query->bind_result($distinct_users_for_the_folder);

                        $check_if_subfolder_only_used_by_current_user_query->fetch();
                        echo "<br>number of distinct users for a filled folder (with bookmarks in this particular folder, not the possible subfolders): ".$check_if_subfolder_only_used_by_current_user_query->num_rows();
                        
                        if($check_if_subfolder_only_used_by_current_user_query->num_rows() > 1){
                            $leave_folders_alone=true;
                            break;
                        }
                        
                        $check_if_subfolder_only_used_by_current_user_query->free_result();
                    }

                //}
            }



            $retrieve_all_subfolders_inside_the_folder_query->free_result();
        }

    
        if($leave_folders_alone==true){
            echo "<br>More than 1 user with bookmarks was found for a subfolder, delete only the bookmark user references (and possibly bookmarks) for all the subfolders";
            foreach($all_subfolders_as_an_array as $subfolder_key=>$subfolder_name){
                $delete_subfolder_bookmarks_for_the_user_query=$connection->prepare("DELETE FROM bookmarksofusers WHERE tags_id=(SELECT tags_id FROM tagsofbookmarks WHERE tags=?");
                $delete_subfolder_bookmarks_for_the_user_query=$connection->bind_param("s",$subfolder_name);

                $delete_subfolder_bookmarks_for_the_user_query->execute();
                
                $_SESSION["successfully_deleted_total_bookmark_count"]=$_SESSION["successfully_deleted_total_bookmark_count"]+1;
            
            }
            exit();
            header('Location: ../../index.php?page=bookmarks_page&bookmark_folder_deleted_status=yes');
        }

        else{
            echo "<br>only 1 user found for all the subfolders (the current user), it is safe to delete folders first";
            //cascade handles deletion automatically for bookmarksofusers
            foreach($all_subfolders_as_an_array as $subfolder_key=>$subfolder_name){
                
                $retrieve_redundant_bookmarks_to_delete_query=$connection->prepare("SELECT url FROM bookmarksofusers WHERE tags_id=(SELECT tags_id FROM tagsofbookmarks WHERE tags=?) AND username=?");
                $retrieve_redundant_bookmarks_to_delete_query->bind_param("ss",$subfolder_name,$_SESSION["username"]);

                if($retrieve_redundant_bookmarks_to_delete_query->execute()){
                    $retrieve_redundant_bookmarks_to_delete_query->store_result();
                    $retrieve_redundant_bookmarks_to_delete_query->bind_result($redundant_bookmarks_to_delete);

                    
                    echo "<br>subfolder to delete: ".$subfolder_name;
                    $delete_redundant_subfolder_query=$connection->prepare("DELETE FROM tagsofbookmarks WHERE tags=?");
                    $delete_redundant_subfolder_query->bind_param("s", $subfolder_name);
                    $delete_redundant_subfolder_query->execute();

                    
                    if($redundant_bookmarks_to_delete != null){
                        while($retrieve_redundant_bookmarks_to_delete->fetch()){
                            //finally delete orphan bookmarks without any users
                            echo "<br>deleting redundant bookmark: ".$redundant_bookmarks_to_delete." inside the subfolder: ".$subfolder_name;
                            $delete_redundant_bookmarks_query=$connection->prepare("DELETE FROM bookmark WHERE WHERE url=?");
                            $delete_redundant_bookmarks_query->bind_param("s",$redundant_bookmarks_to_delete);
                            $_SESSION["successfully_deleted_total_bookmark_count"]=$_SESSION["successfully_deleted_total_bookmark_count"]+1;

                        }
                    }
                    
                    $retrieve_redundant_bookmarks_to_delete_query->free_result();
                    exit();
                }

            }

            
            header('Location: ../../index.php?page=bookmarks_page&bookmark_folder_deleted_status=yes');
        }


    }catch(Exception $e){
        //database error
        echo $e;
        header('Location: ../index.php?page=bookmarks_page&bookmark_folder_deleted_status=no');
    }


}




?>