<?php

if(isset($_GET["user_deleted_status"])){
    if($_GET["user_deleted_status"] == "yes"){
        echo "<br><span class=\"successmessage\">User deleted <a href=\"./index.php?page=user_list\">Refresh page</a></span>";
    }
    else{
        echo "<br><span class=\"errormessage\">User deletion failed, database error <a href=\"./index.php?page=user_list\">Refresh page</a></span>";
    }
}


?>