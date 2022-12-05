
<div id="pagecontent">
<p>
<span class="paragraphtitle">Import your bookmarks</span>
<form method="post" enctype="multipart/form-data" action=".\Eventhandlers\handle_import_bookmarks.php">
<label for="bookmarks_file">Load your browser bookmarks file (.html) from your computer.</label>
<input type="file" id="bookmarks_file" name="bookmarks_file" value="browse files">
<input type="submit" value="Upload the bookmarks file">
</form>
</p>
<p>
<span class="paragraphtitle">Your bookmarks</span>
<?php




    $get_users_bookmarks_query=$connection->prepare("SELECT bookmarksofusers.url, name, tagsofbookmarks.tags, bookmarksofusers.database_creation_date, bookmarksofusers.database_last_modified FROM bookmarksofusers INNER JOIN bookmark ON bookmarksofusers.url=bookmark.url AND username=? INNER JOIN tagsofbookmarks ON tagsofbookmarks.tags_id=bookmarksofusers.tags_id");
    $get_users_bookmarks_query->bind_param("s",$_SESSION["username"]);
    try{
    if($get_users_bookmarks_query->execute()){
        
        $get_users_bookmarks_query->store_result();
        $get_users_bookmarks_query->bind_result($user_bookmark_urls,$user_bookmark_names,$user_bookmark_tags,$user_bookmark_url_database_creation_dates,$user_bookmark_url_database_last_modified_dates);
        echo "<p>".$get_users_bookmarks_query->num_rows()." bookmarks in total</p>";
        echo "<table class=\"bookmarktable\">";
        
        echo "<tr><td>URL</td><td>Folder</td><td>Database creation dare</td><td>Database last modified dates</td></tr>";
        

                while($get_users_bookmarks_query->fetch()){
                    //while($get_current_bookmark_name_query->fetch()){

                        echo "<tr>";
                        echo "<td><a href=\"$user_bookmark_urls\">$user_bookmark_names</a></td><td>$user_bookmark_tags</td><td>$user_bookmark_url_database_creation_dates</td><td>$user_bookmark_url_database_last_modified_dates</td>";
                        echo "<td><form method=\"post\" action=\"Eventhandlers\\handle_delete_bookmark.php\"><input type=\"submit\" name=\"delete_bookmark\" id=\"delete_bookmark\" value =\"DELETE BOOKMARK ".$user_bookmark_urls."\" style=\"color:red\"></form></td>"; }
                        echo "<tr>";
                        
                    //}
                }
                //$get_current_bookmark_name_query->free_result();
            //}
        

        echo "</table>";

        $get_users_bookmarks_query->free_result();

    
    }catch(Exception $e){
        header('./index.php?page=bookmarks_page&bookmarks_retrieved_status=no');
        exit();
    }



?>
</p>
</div>








