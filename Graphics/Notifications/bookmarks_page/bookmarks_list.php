<?php
if(isset($_GET['bookmarks_retrieved_status'])){
    if($_GET['bookmarks_file_upload_status']=='yes'){
        echo "<br><span class=\"successmessage\">Retrieving bookmarks succeeded.</span>";
    }


    elseif($_GET['bookmarks_retrieved_status']=='no'){   
        if($_GET['error']=='bookmarks_not_retrieved'){
            echo "<br><span class=\"errormessage\">Retrieving bookmarks failed, please try refreshing the page. <a href=\"./index.php?page=bookmarks_page\">Refresh page</a></span>";
        }      
    }

}

if(isset($_GET['bookmark_deleted_status'])){

    if($_GET['bookmark_deleted_status']=='yes'){
        echo "<br><span class=\"successmessage\">Deleting bookmark succeeded.</span>";
    }


    elseif($_GET['bookmark_deleted_status']=='no'){   
            echo "<br><span class=\"errormessage\">Deleting bookmarks failed, please retry or try refreshing the page. <a href=\"./index.php?page=bookmarks_page\">Refresh page</a></span>"; 
    }


}



if(isset($_GET['bookmark_folder_deleted_status'])){

    if($_GET['bookmark_folder_deleted_status']=='yes'){
        if(isset($_SESSION["successfully_deleted_total_bookmark_count"])){
        echo "<br><span class=\"successmessage\">Deleting bookmark folder(s) succeeded. ".$_SESSION["successfully_deleted_total_bookmark_count"]." bookmarks deleted in total.</span>";
        }
        
    }


    elseif($_GET['bookmark_folder_deleted_status']=='no'){   
            echo "<br><span class=\"errormessage\">Deleting bookmark folder failed, please retry or try refreshing the page. <a href=\"./index.php?page=bookmarks_page\">Refresh page</a></span>"; 
    }


}

?>



