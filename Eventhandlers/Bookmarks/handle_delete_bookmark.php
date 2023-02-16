<?php

//TODO: incomplete

if(isset($_POST["delete_bookmark"]) && isset($_POST["delete_bookmark_url"]) && isset($_POST["delete_bookmark_tags_id"])){
    //needed because tags_id is returned as string from bookmark page list query
    $delete_bookmarks_tags_id_from_post=intval($_POST["delete_bookmark_tags_id"]);
    

    
    try{

        require dirname(dirname((__DIR__))).'/vendor/autoload.php';
        $dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
        $DOTENVDATA=$dotenv->load();

        require_once('../connect_database.php');
        $bookmark_from_post=$_POST["delete_bookmark_url"];




        
        $check_url_and_tags_id_count_query=$connection->prepare("SELECT COUNT(url), COUNT(tags_id) FROM bookmarksofusers WHERE url=? AND tags_id=?");
        $check_url_and_tags_id_count_query->bind_param("si",$bookmark_from_post, $delete_bookmarks_tags_id_from_post);
        
        if($check_url_and_tags_id_count_query->execute()){
            echo "<br>SUCCEEDED CHECKING FOR ALL USERS OF THE BOOKMARK WITH CERTAIN TAGS";
            
            $check_url_and_tags_id_count_query->store_result();
            $check_url_and_tags_id_count_query->bind_result($bookmark_url_count, $tags_id_count);
            
            while($check_url_and_tags_id_count_query->fetch()){
                
                
                if($bookmark_url_count == 1){
                    //TODO: deletes often several (similar?) URL here
                    if($tags_id_count == 1){
                        /*Both counts are 1, it is safe to delete the tags and user references aswell before deleting the bookmark itself, 
                        cascade deletes the correct lines in bookmarksofusers automatically*/
                        $delete_redundant_tags_query=$connection->prepare("DELETE FROM tagsofbookmarks WHERE tags_id=?");
                        $delete_redundant_tags_query->bind_param("i", $delete_bookmarks_tags_id_from_post);
                        if($delete_redundant_tags_query->execute()){
                            $delete_redundant_tags_query->store_result();
                            $delete_redundant_bookmark_query=$connection->prepare("DELETE FROM bookmark WHERE url=?");
                            $delete_redundant_bookmark_query->bind_param("s", $bookmark_from_post);
                            if($delete_redundant_bookmark_query->execute()){
                                echo "<br> URL COUNT 1, TAGS COUNT 1, SUCCEEDED DELETING REDUNDANT BOOKMARK";
                                exit();
                                header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                            }
                            $delete_redundant_tags_query->free_result();
                        }
                
                    }
                    //TODO: untested
                    elseif($tags_id_count > 1){
                        //only delete the user reference and bookmark itself, bookmark exists for the user only, but the tags (folders) exist for other users aswell
                        $delete_bookmark_for_the_user_query=$connection->prepare("DELETE FROM bookmarksofusers WHERE username=?");
                        $delete_bookmark_for_the_user_query->bind_param("s", $_SESSION["username"]);
                        if($delete_bookmark_for_the_user_query->execute()){
                            $delete_bookmark_for_the_user_query->store_result();
                            $delete_redundant_bookmark_query=$connection->prepare("DELETE FROM bookmark WHERE url=?");
                            $delete_redundant_bookmark_query->bind_param("s", $bookmark_from_post);
                            if($delete_redundant_bookmark_query->execute()){
                                echo "<br> URL COUNT 1, TAGS COUNT 2 OR MORE, SUCCEEDED DELETING REDUNDANT REFERENCES AND BOOKMARK";
                                exit();
                                header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                            }
                            $delete_bookmark_for_the_user_query->free_result();
                        }
                    }
                }
                
                elseif($bookmark_url_count > 1){
                    //TODO: untested
                    if($tags_id_count == 1){
                        //delete tags and bookmark user references only, cascade handles deleting the correct lines in bookmarksofusers
                        $delete_redundant_tags_query=$connection->prepare("DELETE FROM tagsofbookmarks WHERE tags_id=?");
                        $delete_redundant_tags_query->bind_param("i", $delete_bookmarks_tags_id_from_post);
                        if($delete_redundant_tags_query->execute()){  
                            echo "<br> URL COUNT 2 OR MORE, TAGS COUNT 1, SUCCEEDED DELETING REDUNDANT TAGS AND REFERENCES";
                            exit();                
                            header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                        }
                    }
                    //TODO: untested
                    elseif($tags_id_count > 1){
                        //delete only the user reference, both the bookmark itself and its tags (folders) exists for several users, leave bookmarks and tags alone
                        $delete_bookmark_for_the_user_query=$connection->prepare("DELETE FROM bookmarksofusers WHERE username=?");
                        $delete_bookmark_for_the_user_query->bind_param("s", $_SESSION["username"]);
                        if($delete_bookmark_for_the_user_query->execute()){
                            echo "<br> URL COUNT 1, TAGS COUNT 2 OR MORE, SUCCEEDED DELETING REDUNDANT BOOKMARK REFERENCE";
                            exit();
                            header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                        }
                    }

                }
                
            
                
            }
            
            $check_url_and_tags_id_count_query->free_result();
        }
    }catch(Exception $e){
        //database error
        echo $e;
        exit();
        header('Location: ../index.php?page=bookmarks_page&bookmark_deleted_status=no');
        
    }
}





?>