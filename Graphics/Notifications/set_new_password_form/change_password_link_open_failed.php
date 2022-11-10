<?php

if(!isset($_GET['database_error'])){
    echo "<br><span class=\"errormessage\">Opening password change link failed, user email not found based on link data <a href=\"./index.php?page=forgotten_password_form\">Refresh page</a></span>";                   
}
else{
    echo "<br><span class=\"errormessage\">Opening password change link failed, database error while trying to find the user <a href=\"./index.php?page=forgotten_password_form\">Refresh page</a></span>";     
}


?>