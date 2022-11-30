<?php

if(isset($_GET['user_information_edited_status'])){
    if($_GET['user_information_edited_status']=='yes'){
        echo "<br><span class=\"successmessage\">User profile information edited <a href=\"./index.php?page=user_profile\">Refresh page</a></span>";
    }
    else{
        echo "<br><span class=\"errormessage\">User profile information editing failed <a href=\"./index.php?page=user_profile\">Refresh page</a></span>";
    }
}




?>