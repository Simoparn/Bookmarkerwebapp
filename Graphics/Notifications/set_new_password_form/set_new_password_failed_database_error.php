<?php


    if($_GET['correct_email']=="yes"){
        echo "<br><span class=\"errormessage\">Setting new password failed, New password and password confirm do not match, please open the change link and try again <a href=\"./index.php?page=forgotten_password_form\">Refresh page</a></span>";         
    }
    else{
        echo "<br><span class=\"errormessage\">Setting new password failed, the given user email could not be found, please open the change link and try again <a href=\"./index.php?page=forgotten_password_form\">Refresh page</a></span>";                                   
    }
    



?>