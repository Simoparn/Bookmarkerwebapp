<?php
function create_tags_and_set_user_for_the_bookmark($connection, $current_url, $current_tags){
    try{
        $dummy_bookmark_reference_for_preserving_empty_folders="DUMMY_BOOKMARK_FOR_PRESERVING_EMPTY_FOLDERS";
        //echo "\nCURRENT TAGS: ".$current_tags.", CURRENT BOOKMARK:".$current_url."\n";
        //check if the bookmark folder hierarchy already exists in the database
        $check_if_tags_already_exist_query=$connection->prepare("SELECT COUNT(tags) FROM tagsofbookmarks WHERE tags=?");
        $check_if_tags_already_exist_query->bind_param("s",$current_tags);

        if($check_if_tags_already_exist_query->execute()){

            $check_if_tags_already_exist_query->store_result();
            $check_if_tags_already_exist_query->bind_result($tags_count);
            $check_if_tags_already_exist_query->fetch();

            if($tags_count == 0){
                echo "TAGS count 0, CREATING \n";
                $save_tags_to_database_query=$connection->prepare("INSERT INTO tagsofbookmarks(tags) VALUES (?)");
                $save_tags_to_database_query->bind_param("s",$current_tags);
                
                if($save_tags_to_database_query->execute()){
                    $save_tags_to_database_query->store_result();
                    $retrieve_tags_id_query=$connection->prepare("SELECT tags_id FROM tagsofbookmarks WHERE tags =?");
                    $retrieve_tags_id_query->bind_param("s",$current_tags);
                    if($retrieve_tags_id_query->execute()){
                        $retrieve_tags_id_query->store_result();
                        $retrieve_tags_id_query->bind_result($tags_id);
                        $retrieve_tags_id_query->fetch();
                        $check_that_user_bookmark_with_tags_doesnt_exist_query=$connection->prepare("SELECT COUNT(username) FROM bookmarksofusers WHERE url=? AND tags_id=? AND username=?");
                        $check_that_user_bookmark_with_tags_doesnt_exist_query->bind_param("sis", $current_url, $tags_id, $_SESSION["username"]);
                        if($check_that_user_bookmark_with_tags_doesnt_exist_query->execute()){

                            $check_that_user_bookmark_with_tags_doesnt_exist_query->store_result();
                            $check_that_user_bookmark_with_tags_doesnt_exist_query->bind_result($user_bookmark_in_folder_count);
                            $check_that_user_bookmark_with_tags_doesnt_exist_query->fetch();
                            if($user_bookmark_in_folder_count == 0){
                                //remember to create user references to the previously created dummy invisible bookmark for preserving empty folders in UI
                                //$dummy_bookmark_reference_for_preserving_empty_folders="DUMMY_BOOKMARK_FOR_PRESERVING_EMPTY_FOLDERS";
                                
                                $insert_dummy_bookmark_for_user_folder_query=$connection->prepare("INSERT IGNORE INTO bookmarksofusers(username, url, tags_id) VALUES(?,?,?)");
                                $insert_dummy_bookmark_for_user_folder_query->bind_param("ssi", $_SESSION["username"], $dummy_bookmark_reference_for_preserving_empty_folders,  $tags_id);

                                if($insert_dummy_bookmark_for_user_folder_query->execute()){
                                    $insert_dummy_bookmark_for_user_folder_query->store_result();
                                    $save_user_with_this_bookmark_and_these_tags_query=$connection->prepare("INSERT INTO bookmarksofusers(username, url, tags_id) VALUES(?,?,(SELECT MAX(tags_id) FROM tagsofbookmarks))");
                                    $save_user_with_this_bookmark_and_these_tags_query->bind_param("ss", $_SESSION["username"], $current_url);
                                
                                    if($save_user_with_this_bookmark_and_these_tags_query->execute()){
                                
                                        //Simply continue to the next url after success
                                        return true;
                                    }
                                    else{
                                        echo "\nSAVING USER WITH THIS BOOKMARK AND THESE TAGS FAILED";
                                        return false;
                                        
                                    }
                                    $insert_dummy_bookmark_for_user_folder_query->free_result();
                                }
                                
                            }
                            /*else{
                                echo "USER BOOKMARKS IN FOLDER COUNT = 0\n";
                            }*/
                            $check_that_user_bookmark_with_tags_doesnt_exist_query->free_result();
                        }
                        $retrieve_tags_id_query->free_result();
                    }
                    else{
                        echo "\nRETRIEVING TAGS ID FAILED";
                        return false;
                    }
                    $save_tags_to_database_query->free_result();

                }
                else{
                    echo "Couldn't save new tags";
                    return false;
                    
                }

            }
            else{
                //If both the bookmark and the folder hierarchy already exists for several users, simply appoint the current user for the bookmark that has the specific folder hierarchy location
                echo "\nTAGS count 1 or over \n";
                $retrieve_tags_id_query=$connection->prepare("SELECT tags_id FROM tagsofbookmarks WHERE tags =?");
                $retrieve_tags_id_query->bind_param("s",$current_tags);
                
                if($retrieve_tags_id_query->execute()){
                    $retrieve_tags_id_query->store_result();
                    $retrieve_tags_id_query->bind_result($tags_id);
                    $retrieve_tags_id_query->fetch();

                    
                    

                    
                    
                    
                    $check_that_user_bookmark_with_tags_doesnt_exist_query=$connection->prepare("SELECT COUNT(username) FROM bookmarksofusers WHERE url=? AND tags_id=? AND username=?");
                    $check_that_user_bookmark_with_tags_doesnt_exist_query->bind_param("sis", $current_url, $tags_id, $_SESSION["username"]);

                    if($check_that_user_bookmark_with_tags_doesnt_exist_query->execute()){
                        $check_that_user_bookmark_with_tags_doesnt_exist_query->store_result();
                        $check_that_user_bookmark_with_tags_doesnt_exist_query->bind_result($user_bookmark_in_folder_count);
                        $check_that_user_bookmark_with_tags_doesnt_exist_query->fetch();
                        if($user_bookmark_in_folder_count==0){
                            echo "\nbookmark folder count for user 0";
                            
                            //remember to create user references to the dummy bookmark if created previously
                            $check_if_dummy_bookmark_with_tags_exist_already_for_user_query=$connection->prepare("SELECT COUNT(url) FROM bookmarksofusers WHERE username=? AND url=? AND tags_id=?");
                            $check_if_dummy_bookmark_with_tags_exist_already_for_user_query->bind_param("ssi", $_SESSION["username"], $dummy_bookmark_reference_for_preserving_empty_folders,  $tags_id);
                            if($check_if_dummy_bookmark_with_tags_exist_already_for_user_query->execute()){
                                $check_if_dummy_bookmark_with_tags_exist_already_for_user_query->store_result();
                                $check_if_dummy_bookmark_with_tags_exist_already_for_user_query->bind_result($dummy_bookmark_with_tags_count);
                                $check_if_dummy_bookmark_with_tags_exist_already_for_user_query->fetch();
                                //echo "COUNT:".$dummy_bookmark_with_tags_count;
                                //exit();
                                if($dummy_bookmark_with_tags_count==0){
                                    echo "\nDUMMY BOOKMARK WITH TAGS COUNT 0";
                                    $insert_dummy_bookmark_with_tags_for_user_query=$connection->prepare("INSERT IGNORE INTO bookmarksofusers(username, url, tags_id) VALUES(?,?,?)");
                                    $insert_dummy_bookmark_with_tags_for_user_query->bind_param("ssi", $_SESSION["username"], $dummy_bookmark_reference_for_preserving_empty_folders,  $tags_id);
                                    if($insert_dummy_bookmark_with_tags_for_user_query->execute()){
                                        $insert_dummy_bookmark_with_tags_for_user_query->store_result();

                                        $save_user_with_this_bookmark_and_these_tags_query=$connection->prepare("INSERT INTO bookmarksofusers(username, url, tags_id) VALUES(?,?,?)");
                                        $save_user_with_this_bookmark_and_these_tags_query->bind_param("ssi", $_SESSION["username"], $current_url, $tags_id);
                    
                                        if($save_user_with_this_bookmark_and_these_tags_query->execute()){
                                            //Simply continue to the next url after success
                                            return true;
                                        }
                                        else{
                                            echo "\nSAVING USER WITH THIS BOOKMARKS AND THESE TAGS FAILED";
                                            return false;
                                        }
                                        $insert_dummy_bookmark_with_tags_for_user_query->free_result();
                                    }
                                }
                                else{
                                    echo "\nDUMMY BOOKMARK WITH TAGS COUNT 1 OR OVER";
                                    $save_user_with_this_bookmark_and_these_tags_query=$connection->prepare("INSERT INTO bookmarksofusers(username, url, tags_id) VALUES(?,?,?)");
                                    $save_user_with_this_bookmark_and_these_tags_query->bind_param("ssi", $_SESSION["username"], $current_url, $tags_id);
                    
                                    if($save_user_with_this_bookmark_and_these_tags_query->execute()){
                                        //Simply continue to the next url after success
                                        return true;
                                    }
                                    else{
                                        echo "\nSAVING USER WITH THIS BOOKMARKS AND THESE TAGS FAILED";
                                        return false;
                            
                                    }
                                }
                                $check_if_dummy_bookmark_with_tags_exist_already_for_user_query->free_result();
                            }
                            
                        }
                        else{
                            echo "Bookmark folder count for user 1 or over";
                            $save_user_with_this_bookmark_and_these_tags_query=$connection->prepare("INSERT INTO bookmarksofusers(username, url, tags_id) VALUES(?,?,?)");
                            $save_user_with_this_bookmark_and_these_tags_query->bind_param("ssi", $_SESSION["username"], $current_url, $tags_id);
                    
                                if($save_user_with_this_bookmark_and_these_tags_query->execute()){
                                    //Simply continue to the next url after success
                                    return true;
                                    }
                                    else{
                                        echo "\nSAVING USER WITH THIS BOOKMARKS AND THESE TAGS FAILED";
                                        return false;
                                    }

                        }
                        
                        $check_that_user_bookmark_with_tags_doesnt_exist_query->free_result();
                    }
                    $retrieve_tags_id_query->free_result();
                }
                else{
                    echo "\nCouldn't retrieve tags id for bookmarksofusers";
                    return false;
                    
                }
                
            }

            $check_if_tags_already_exist_query->free_result();
            
        }
        else{
            echo "\nTAGS DIDN'T EXIST ALREADY";
            return false;
            
        }

        echo "\nCREATE TAGS AND SET USER, INCORRECT CONDITION CHECK";

    }catch(Exception $e){
        echo $e;
        echo "EXCEPTION IN CREATE TAGS AND SET USER";
        return false;
        
        
    }


}


?>