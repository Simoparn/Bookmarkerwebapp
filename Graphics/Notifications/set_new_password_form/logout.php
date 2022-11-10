<?php

if(isset($_GET['logout_status'])){
    if($_GET['logout_status']=='yes'){
        echo "<br><span class=\"successmessage\">Log out succeeded <a href=\"./index.php?page=set_new_password_form\">Refresh page</a></span>";
    }

    elseif($_GET['logout_status']=='no'){
        echo "<br><span class=\"errormessage\">Log out epäonnistui <a href=\"./index.php?page=set_new_password_form\">Refresh page</a></span>";
    }
}


?>