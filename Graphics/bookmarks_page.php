
<div id="pagecontent">
<p>
<span class="paragraphtitle">Import your bookmarks</span>
<form method="post" enctype="multipart/form-data" action=".\Eventhandlers\handle_load_bookmarks.php">
<label for="bookmarks_file">Load your browser bookmarks file (.html) from your computer.</label>
<input type="file" id="bookmarks_file" name="bookmarks_file" value="browse files">
<input type="submit" value="Upload the bookmarks file">
</form>
</p>
<p>
<span class="paragraphtitle">Your bookmarks</span>
<?php




    $get_users_bookmarks_query=$connection->prepare("SELECT bookmarksofusers.url, name, tags_id, bookmarksofusers.database_creation_date, bookmarksofusers.database_last_modified FROM bookmarksofusers INNER JOIN bookmark ON bookmarksofusers.url=bookmark.url AND username=?");
    $get_users_bookmarks_query->bind_param("s",$_SESSION["username"]);
    try{
    if($get_users_bookmarks_query->execute()){
        
        $get_users_bookmarks_query->store_result();
        $get_users_bookmarks_query->bind_result($user_bookmark_urls,$user_bookmark_names,$user_bookmark_tag_ids,$user_bookmark_url_database_creation_dates,$user_bookmark_url_database_last_modified_dates);
        
        echo "<table class=\"bookmarktable\">";
        
        echo "<tr><td>URL</td><td>Database creation dare</td><td>Database last modified dates</td></tr>";
        
            
                while($get_users_bookmarks_query->fetch()){
                    //while($get_current_bookmark_name_query->fetch()){

                        echo "<tr>";
                        echo "<td><a href=\"$user_bookmark_urls\">$user_bookmark_names</a></td><td>$user_bookmark_url_database_creation_dates</td><td>$user_bookmark_url_database_last_modified_dates</td>";
                        echo "<tr>";
                    //}
                }
                //$get_current_bookmark_name_query->free_result();
            //}
        

        echo "</table>";

        $get_users_bookmarks_query->free_result();

    }
    }catch(Exception $e){
        header('./index.php?page=bookmarks_page&bookmarks_retrieved_status=no');
        exit();
    }



?>
</p>
</div>








