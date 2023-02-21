<?php


if(isset($_GET['bookmarks_file_upload_status'])){
    if($_GET['bookmarks_file_upload_status']=='yes'){
        if(isset($_SESSION['successfully_loaded_bookmark_count'])){
            echo "<br><span class=\"successmessage\">Uploading bookmarks file succeeded, ". $_SESSION['successfully_loaded_bookmark_count']." unique bookmarks loaded. <a href=\"./index.php?page=bookmarks_page\">Refresh page</a></span>";
            unset($_SESSION['successfully_loaded_bookmark_count']);
        }
    }

    elseif($_GET['bookmarks_file_upload_status']=='no'){
        if(isset($_GET['error'])){
            if($_GET['error']=='file_not_sent'){
                echo "<br><span class=\"errormessage\">Uploading bookmarks file failed, no file was given! <a href=\"./index.php?page=bookmarks_page\">Refresh page</a></span>";
            }
            elseif($_GET['error']=='wrong_file_format'){
                echo "<br><span class=\"errormessage\">Uploading bookmarks file failed, file was in wrong format <a href=\"./index.php?page=bookmarks_page\">Refresh page</a></span>";
            }
            elseif($_GET['error']=='filesize_exceeded'){
                echo "<br><span class=\"errormessage\">Uploading bookmarks file failed, filesize exceeded <a href=\"./index.php?page=bookmarks_page\">Refresh page</a></span>";
            }
            elseif($_GET['error']=='database_error'){
                echo "<br><span class=\"errormessage\">Uploading bookmarks file failed, database error <a href=\"./index.php?page=bookmarks_page\">Refresh page</a></span>";
            }
        }
    }
}






?>