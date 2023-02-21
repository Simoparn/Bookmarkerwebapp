<?php



if(isset($_POST["delete_bookmark"]) && isset($_POST["delete_bookmark_url"]) && isset($_POST["delete_bookmark_tags_id"])){
    //needed because tags_id is returned as string from bookmark page list query
    $delete_bookmarks_tags_id_from_post=intval($_POST["delete_bookmark_tags_id"]);
    

    
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
                
                $check_tags_count_query=$connection->prepare("SELECT COUNT(tags_id) FROM bookmarksofusers WHERE tags_id=?");
                $check_tags_count_query->bind_param("i",$delete_bookmarks_tags_id_from_post);
                if($check_tags_count_query->execute()){
                    echo "<br>SUCCEEDED CHECKING FOR HOW MANY OF THE SAME FOLDER EXISTS FOR THE BOOKMARK";
                    $check_tags_count_query->store_result();
                    $check_tags_count_query->bind_result($tags_id_count);
                    while($check_tags_count_query->fetch()){
                        echo gettype($tags_id_count);
                        if($bookmark_url_count == 1){
                            echo "<br>BOOKMARK URL COUNT 1";
                            
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
                                        //echo "<br> URL COUNT $bookmark_url_count, TAGS COUNT $tags_id_count, SUCCEEDED DELETING REDUNDANT BOOKMARK";
                                        //exit();
                                        header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                                    }
                                    $delete_redundant_tags_query->free_result();
                                }
                        
                            }
                            
                            elseif($tags_id_count > 1){
                                //only delete the user reference and bookmark itself, bookmark exists for the user only, but the tags (folders) exist for several users
                                $delete_bookmark_for_the_user_query=$connection->prepare("DELETE FROM bookmarksofusers WHERE url=? AND tags_id=? AND username=?");
                                $delete_bookmark_for_the_user_query->bind_param("sis", $bookmark_From_post, $delete_bookmarks_tags_id_from_post, $_SESSION["username"]);
                                if($delete_bookmark_for_the_user_query->execute()){
                                    $delete_bookmark_for_the_user_query->store_result();
                                    $delete_redundant_bookmark_query=$connection->prepare("DELETE FROM bookmark WHERE url=?");
                                    $delete_redundant_bookmark_query->bind_param("s", $bookmark_from_post);
                                    if($delete_redundant_bookmark_query->execute()){
                                        header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                                    }
                                    $delete_bookmark_for_the_user_query->free_result();
                                }
                            }
                        }
                        
                        elseif($bookmark_url_count > 1){
                            echo "<br>BOOKMARK URL COUNT 2 OR MORE";
                            if($tags_id_count == 1){
                                //delete tags and bookmark user references only, cascade handles deleting the correct lines in bookmarksofusers
                                $delete_redundant_tags_query=$connection->prepare("DELETE FROM tagsofbookmarks WHERE tags_id=?");
                                $delete_redundant_tags_query->bind_param("i", $delete_bookmarks_tags_id_from_post);
                                if($delete_redundant_tags_query->execute()){  
                                    //echo "<br> URL COUNT $bookmark_url_count, $tags_id_count, SUCCEEDED DELETING REDUNDANT TAGS AND REFERENCES";
                                    //exit();                
                                    header('Location: ../../index.php?page=bookmarks_page&bookmark_deleted_status=yes');
                                }
                            }
                            
                            elseif($tags_id_count > 1){
                                //delete only the user reference, both the bookmark itself and its tags (folder) exists for several users, leave bookmarks and tags alone
                                $delete_bookmark_for_the_user_query=$connection->prepare("DELETE FROM bookmarksofusers WHERE url=? AND tags_id=? AND username=?");
                                $delete_bookmark_for_the_user_query->bind_param("sis",$bookmark_from_post, $delete_bookmarks_tags_id_from_post, $_SESSION["username"]);
                                if($delete_bookmark_for_the_user_query->execute()){
                                    //echo "<br> URL COUNT $bookmark_url_count, TAGS COUNT $tags_id_count, SUCCEEDED DELETING REDUNDANT BOOKMARK REFERENCE ALONE";
                                    //exit();
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
        header('Location: ../index.php?page=bookmarks_page&bookmark_deleted_status=no');
        
    }
}





?>