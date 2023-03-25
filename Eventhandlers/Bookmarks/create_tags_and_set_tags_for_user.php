<?php

function create_tags_and_set_tags_for_user($connection, $current_tags){

    try{

        $bookmark_folder_path_as_array=explode(" ", $current_tags);
        $current_folder_path="";
        $dummy_bookmark_reference_for_preserving_empty_folders="DUMMY_BOOKMARK_FOR_PRESERVING_EMPTY_FOLDERS";
        
        foreach($bookmark_folder_path_as_array as $subfolder_key => $subfolder_value){
            
            if($current_folder_path == ""){
                $current_folder_path=$subfolder_value;
            }
            else{
            $current_folder_path=$current_folder_path." ".$subfolder_value;
            }

            //echo "\nCurrent folder path: ".$current_folder_path;
            $check_if_tags_already_exist_query=$connection->prepare("SELECT COUNT(tags) FROM tagsofbookmarks WHERE tags=?");
            $check_if_tags_already_exist_query->bind_param("s",$current_folder_path);


            if($check_if_tags_already_exist_query->execute()){

                $check_if_tags_already_exist_query->store_result();
                $check_if_tags_already_exist_query->bind_result($tags_count);
                $check_if_tags_already_exist_query->fetch();


                if($tags_count == 0){
                    //echo "\nfolder path doesn't exist, creating.";
                    $save_tags_to_database_query=$connection->prepare("INSERT INTO tagsofbookmarks(tags) VALUES (?)");
                    $save_tags_to_database_query->bind_param("s",$current_folder_path);
                    if($save_tags_to_database_query->execute()){
                        $save_tags_to_database_query->store_result();
                        $retrieve_tags_id_query=$connection->prepare("SELECT tags_id FROM tagsofbookmarks WHERE tags =?");
                        //echo "CURRENT PATH: ".$current_folder_path;
                        //exit();
                        $retrieve_tags_id_query->bind_param("s",$current_folder_path);
                        if($retrieve_tags_id_query->execute()){
                            //echo "\nretrieving tags id succeeded";
                            $retrieve_tags_id_query->store_result();
                            $retrieve_tags_id_query->bind_result($tags_id);
                            $retrieve_tags_id_query->fetch();
                            //remember to create user references to the previously created dummy invisible bookmark for preserving empty folders in UI
                            $insert_dummy_bookmark_for_user_folder_query=$connection->prepare("INSERT IGNORE INTO bookmarksofusers(username, url, tags_id) VALUES(?,?,?)");
                            $insert_dummy_bookmark_for_user_folder_query->bind_param("ssi", $_SESSION["username"], $dummy_bookmark_reference_for_preserving_empty_folders,  $tags_id);

                            if($insert_dummy_bookmark_for_user_folder_query->execute()){
                                //echo "\ninserting dummy bookmark reference succeeded";
                                $insert_dummy_bookmark_for_user_folder_query->store_result();
                                continue;
                                $insert_dummy_bookmark_for_user_folder_query->free_result();
                            }
                            else{
                                return false;
                            }
                            $retrieve_tags_id_query->free_result();
                        }


                        $save_tags_to_database_query->free_result();
                    }
                    //echo "\nfaulty condition check";

                }
                //tags exist already for some users
                else{ 
                    //echo "\nfolder path exists already";   
                    $retrieve_tags_id_query=$connection->prepare("SELECT tags_id FROM tagsofbookmarks WHERE tags =?");
                    //echo "CURRENT PATH: ".$current_folder_path;
                    //exit();
                    $retrieve_tags_id_query->bind_param("s",$current_folder_path);
                    if($retrieve_tags_id_query->execute()){
                        //echo "\nretrieving tags id succeeded";
                        $retrieve_tags_id_query->store_result();
                        $retrieve_tags_id_query->bind_result($tags_id);
                        $retrieve_tags_id_query->fetch();
                        //remember to create user references to the previously created dummy invisible bookmark for preserving empty folders in UI
                        $insert_dummy_bookmark_for_user_folder_query=$connection->prepare("INSERT IGNORE INTO bookmarksofusers(username, url, tags_id) VALUES(?,?,?)");
                        $insert_dummy_bookmark_for_user_folder_query->bind_param("ssi", $_SESSION["username"], $dummy_bookmark_reference_for_preserving_empty_folders,  $tags_id);
                        if($insert_dummy_bookmark_for_user_folder_query->execute()){
                            //echo "\ninserting dummy bookmark reference succeeded";
                            $insert_dummy_bookmark_for_user_folder_query->store_result();
                            continue;
                            $insert_dummy_bookmark_for_user_folder_query->free_result();
                        }
                        else{
                            return false;
                        }
                        $retrieve_tags_id_query->free_result();
                    }

                    //echo "\nfaulty condition check";
                    
                }
                $check_if_tags_already_exist_query->free_result();
            }
            else{
                return false;
            }

            
            
            
        }

        return true;

    }catch(Exception $e){
        //echo "EXCEPTION IN CREATE TAGS FOR USER\n";
        echo $e;
        return false;
    }
}



?>