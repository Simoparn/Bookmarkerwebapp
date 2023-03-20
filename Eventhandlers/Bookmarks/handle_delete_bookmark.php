<?php



if(isset($_POST["delete_bookmark"]) && isset($_POST["delete_bookmark_url"]) && isset($_POST["delete_bookmark_tags_id"])){
    //needed because tags_id is returned as string from bookmark page list query
    $delete_bookmark_tags_id_from_post=intval($_POST["delete_bookmark_tags_id"]);
    $dummy_bookmark_for_preserving_empty_folders="DUMMY_BOOKMARK_FOR_PRESERVING_EMPTY_FOLDERS";

    
    try{

        require dirname(dirname((__DIR__))).'/vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
        $DOTENVDATA=$dotenv->load();

        require_once('../connect_database.php');
        $bookmark_from_post=$_POST["delete_bookmark_url"];




        
        $check_url_count_query=$connection->prepare("SELECT COUNT(url) FROM bookmarksofusers WHERE url=?");
        $check_url_count_query->bind_param("s",$bookmark_from_post);
        
        if($check_url_count_query->execute()){
            echo "<br>SUCCEEDED CHECKING FOR ALL USERS OF THE BOOKMARK";
            
            $check_url_count_query->store_result();
            $check_url_count_query->bind_result($bookmark_url_count);
            
            while($check_url_count_query->fetch()){
                
                $check_tags_count_query=$connection->prepare("SELECT COUNT(tags_id) FROM bookmarksofusers WHERE url=? AND tags_id=?");
                $check_tags_count_query->bind_param("si",$bookmark_from_post, $delete_bookmark_tags_id_from_post);
                if($check_tags_count_query->execute()){
                    echo "<br>SUCCEEDED CHECKING FOR HOW MANY OF THE SAME FOLDER EXISTS FOR THE BOOKMARK (MANY USERS CAN HAVE IT)";
                    $check_tags_count_query->store_result();
                    $check_tags_count_query->bind_result($tags_id_count);
                    while($check_tags_count_query->fetch()){
                        //echo gettype($tags_id_count);
                        if($bookmark_url_count == 1){
                            echo "<br>BOOKMARK URL COUNT 1";
                            
                            if($tags_id_count == 1){
                                
                                
                                /*Both counts are 1, it is safe to delete the tags and user references aswell before deleting the bookmark itself, 
                                cascade deletes the correct lines in bookmarksofusers automatically*/
                                /*
                                $delete_redundant_tags_query=$connection->prepare("DELETE FROM tagsofbookmarks WHERE tags_id=?");
                                $delete_redundant_tags_query->bind_param("i", $delete_bookmark_tags_id_from_post);
                                if($delete_redundant_tags_query->execute()){
                                    $delete_redundant_tags_query->store_result();
                                    $delete_redundant_bookmark_query=$connection->prepare("DELETE FROM bookmark WHERE url=?");
                                    $delete_redundant_bookmark_query->bind_param("s", $bookmark_from_post);
                                    if($delete_redundant_bookmark_query->execute()){
                                        //echo "<br> URL COUNT $bookmark_url_count, TAGS COUNT $tags_id_count, SUCCEEDED DELETING REDUNDANT BOOKMARK";
                                        //exit();
                                        header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                                    }
                                    $delete_redundant_tags_query->free_result();
                                }
                                */


                                
                                //TODO:untested
                                //don't remove empty folder here
                                //Bookmark itself and bookmark-folder combination exists for the user only, it is safe to consider the folder for the user empty (of links, not necessarily subfolders)
                                

                                $delete_bookmark_query=$connection->prepare("DELETE FROM bookmark WHERE url=?");
                                $delete_bookmark_query->bind_param("s", $bookmark_from_post);
                                if($delete_bookmark_query->execute()){
                                    $delete_bookmark_query->store_result();
                                    //$delete_bookmark_for_the_user_query=$connection->prepare("DELETE FROM bookmarksofusers WHERE username=? AND url=? AND tags_id=?");
                                    //$delete_bookmark_for_the_user_query->bind_param("ssi", $_SESSION["username"], $bookmark_from_post, $delete_bookmark_tags_id_from_post);
                                    //if($delete_bookmark_for_the_user_query->execute()){*/
                                    echo "<br>1 BOOKMARK, 1 TAG";
                                    exit();
                                    header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                                    //}
                                                  
                                }
                                
                            }
                            
                            elseif($tags_id_count > 1){
                                //only delete the user reference and bookmark itself, bookmark exists for the user only, but the tags (folders) exist for several users
                                
                                /*
                                $delete_bookmark_for_the_user_query=$connection->prepare("DELETE FROM bookmarksofusers WHERE url=? AND tags_id=? AND username=?");
                                $delete_bookmark_for_the_user_query->bind_param("sis", $bookmark_From_post, $delete_bookmark_tags_id_from_post, $_SESSION["username"]);
                                if($delete_bookmark_for_the_user_query->execute()){
                                    $delete_bookmark_for_the_user_query->store_result();
                                    $delete_redundant_bookmark_query=$connection->prepare("DELETE FROM bookmark WHERE url=?");
                                    $delete_redundant_bookmark_query->bind_param("s", $bookmark_from_post);
                                    if($delete_redundant_bookmark_query->execute()){
                                        header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                                    }
                                    $delete_bookmark_for_the_user_query->free_result();
                                }
                                */

                                
                                //TODO: untested, don't remove empty folder here, instead insert a special 'empty' URL that is not shown when retrieving bookmarks in UI
                                // bookmark exists for the user only, but the tags (folders) exist for several users
                                $delete_bookmark_query=$connection->prepare("DELETE FROM bookmark WHERE url=?");
                                $delete_bookmark_query->bind_param("s", $bookmark_from_post);

                                if($delete_bookmark_query->execute()){
                                    $delete_bookmark_query->store_result();

                                        echo "<br>1 BOOKMARK, MULTIPLE TAGS";
                                        exit();
                                        header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                                    
                                }
                                
                                


                            }
                        }
                        
                        elseif($bookmark_url_count > 1){

                            echo "<br>BOOKMARK URL COUNT 2 OR MORE";
                            if($tags_id_count == 1){

                                //bookmark can exist for several users, but this exact bookmark-folder combination exists only for the current user
                                
                                
                                /*TODO: untested, don't remove empty folder here, instead insert a special 'empty' URL that is not shown when retrieving bookmarks in UI
                                bookmark can exist for several users, but this exact bookmark-location combination exists only for the current user, 
                                it is safe to consider the folder for the user empty (of links, not necessarily subfolders) afterwards.
                                No need to delete the bookmark, just create a dummy bookmark if it doesn't exist, then create the reference
                                */
                                        $delete_bookmark_for_the_user_query=$connection->prepare("DELETE FROM bookmarksofusers WHERE username=? AND url=? AND tags_id=?");
                                        $delete_bookmark_for_the_user_query->bind_param("ssi", $_SESSION["username"], $bookmark_from_post, $delete_bookmark_tags_id_from_post);
                                        if($delete_bookmark_for_the_user_query->execute()){
                                            echo "<br>MULTIPLE BOOKMARKS, 1 TAG";
                                            exit();
                                            header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                                        }
                                    
                                    
                                
                                

                            }
                            
                            elseif($tags_id_count > 1){


                                
                                //TODO: untested, still deletes all the empty nested folders in user interface
                                //don't remove empty folder here, instead insert a special 'empty' URL that is not shown when retrieving bookmarks in UI
                                //bookmark exists for several users and the bookmark-location combination exists for several users
                                //delete only the user reference 
                                
                                
                                
                                $delete_bookmark_for_the_user_query=$connection->prepare("DELETE FROM bookmarksofusers WHERE username=? AND url=? AND tags_id=?");
                                $delete_bookmark_for_the_user_query->bind_param("ssi", $_SESSION["username"], $bookmark_from_post, $delete_bookmark_tags_id_from_post);
                                if($delete_bookmark_for_the_user_query->execute()){
                                            
                                    echo "<br>MULTIPLE BOOKMARKS, MULTIPLE TAGS";
                                    exit();
                                    header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');

                                            
                                            
                                }
                            
                                
                            }

                        }

                    }
                    $check_tags_count_query->free_result();
                }
                
            
                
            }
            
            $check_url_count_query->free_result();
        }
    }catch(Exception $e){
        //database error
        echo $e;
        exit();
        header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=no');
        
    }
}





?>