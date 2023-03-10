<?php

    $_SESSION["current_folder_structure_from_top"]="";

    function traverse_and_render_folder_structure($iterator, $connection) {

        $current_folder_structure_from_top="";
        while ( $iterator -> valid() ) {
            
            
            if ( $iterator -> hasChildren() ) {
                if($_SESSION["current_folder_structure_from_top"]!=""){
                    $_SESSION["current_folder_structure_from_top"]=$_SESSION["current_folder_structure_from_top"]." ".$iterator->key();
                }
                else{
                    $_SESSION["current_folder_structure_from_top"]=$iterator->key();
                }
                
                //echo "<br>Current folder structure from top: ";
                //echo $_SESSION["current_folder_structure_from_top"];
                //print_r($current_folder_structure_from_top);
                    
                
                echo "<li><span class=\"caret\" title=\"Open or close the folder\"></span><ul style=\"display:inline-block; list-style-type:none\"><li style=\" display:flex; font-size:125%\">".$iterator->key()."     ";
                echo "<form method=\"post\" action=\"Eventhandlers\\Bookmarks\\handle_delete_bookmark_folder.php\" style=\"padding:0\"><input type=\"hidden\" name=\"delete_current_bookmark_folder_structure_from_top\" id=\"delete_current_bookmark_folder_structure_from_top\" value=\"".$_SESSION["current_folder_structure_from_top"]."\"><input type=\"submit\" name=\"delete_bookmark_folder_name\" id=\"delete_bookmark_folder_name\" class=\"deletebutton\" title=\"Delete the folder\" value=\"❌\"></form></li></ul>";
                //echo ":";
                //print_r($iterator->current());
                //echo "<br> previous array has children<br>";
                

                echo "<ul class=\"folder_nested\">";
                

                
                $get_users_bookmarks_for_folder_query=$connection->prepare("SELECT bookmarksofusers.url, name, tagsofbookmarks.tags, tagsofbookmarks.tags_id, bookmarksofusers.database_creation_date, bookmarksofusers.database_last_modified FROM bookmarksofusers INNER JOIN bookmark ON bookmarksofusers.url=bookmark.url AND username=? INNER JOIN tagsofbookmarks ON tagsofbookmarks.tags_id=bookmarksofusers.tags_id");
                $get_users_bookmarks_for_folder_query->bind_param("s",$_SESSION["username"]);
                if($get_users_bookmarks_for_folder_query->execute()){
                    $get_users_bookmarks_for_folder_query->store_result();
                    $get_users_bookmarks_for_folder_query->bind_result($user_bookmark_url,$user_bookmark_name,$user_bookmark_tags, $user_bookmark_tags_id, $user_bookmark_url_database_creation_date,$user_bookmark_url_database_last_modified_date);
                    echo "<table class=\"bookmarksinsubfolderdata\">";
                    while($get_users_bookmarks_for_folder_query->fetch()){
                        if($_SESSION["current_folder_structure_from_top"]==$user_bookmark_tags && $user_bookmark_url != "DUMMY_BOOKMARK_FOR_PRESERVING_EMPTY_FOLDERS"){
                            //echo "<br>current folder starting from top and retrieved tags match: ".$user_bookmark_tags." for the link: ".$user_bookmark_url;
                            //echo "<br>SESSION data for current folder from top: ".$_SESSION["current_folder_structure_from_top"];
                            
                            echo "<tr>";
                            echo "<td><a href=\"$user_bookmark_url\">$user_bookmark_name</a></td><td>$user_bookmark_url_database_creation_date</td><td>$user_bookmark_url_database_last_modified_date</td>";
                            echo "<td><form method=\"post\" action=\"Eventhandlers\\Bookmarks\\handle_delete_bookmark.php\"><input type=\"hidden\" name=\"delete_bookmark_url\" id=\"delete_bookmark_url\" value=".$user_bookmark_url."><input type=\"hidden\" name=\"delete_bookmark_tags_id\" id=\"delete_bookmark_tags_id\" value=\"$user_bookmark_tags_id\"><input type=\"submit\" name=\"delete_bookmark\" id=\"delete_bookmark\" class=\"deletebutton\" title=\"Delete the bookmark\" value =\"❌\"></form></td>";
                            echo "</tr>";
                            
                        }
                        else{
                            //echo "<br>current folder starting from top not found";
                        }
                    }
                    echo "</table>";
                    $get_users_bookmarks_for_folder_query->free_result();
                }

                //echo $iterator->current();
                traverse_and_render_folder_structure($iterator -> getChildren(), $connection);   
                echo "</ul>";
                echo "</li>";
                
            }

            else {
                $_SESSION["current_folder_structure_from_top"]="";
                //echo "no more subfolders inside:";
                echo $iterator -> key() . ' : ' . $iterator -> current() .PHP_EOL;    

            }

            $current_folder_structure_from_top_length=count(explode(" ",$_SESSION["current_folder_structure_from_top"]));
            //if($current_folder_structure_from_top_length != 0)
            $back_to_upper_folder=explode(" ",$_SESSION["current_folder_structure_from_top"]);
            array_pop($back_to_upper_folder);
            //print_r(gettype($back_to_upper_folder));
            $_SESSION["current_folder_structure_from_top"]=implode(" ",$back_to_upper_folder);
            $iterator -> next();
            

        }

        
    }

    

?>