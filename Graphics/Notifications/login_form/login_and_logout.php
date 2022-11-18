<?php

if(isset($_GET["login_status"])){            
    if($_GET["login_status"] =="no"){ 
        echo "<br><span class=\"errormessage\">Login failed, wrong username or password<a href=\"./index.php?page=login_form\">Refresh page</a></span>";
    }
    elseif($_GET['login_status'] =='unknown_error'){
        echo "<br><span class=\"errormessage\">Login failed, unknown error, please try again <a href=\"./index.php?page=login_form\">Refresh page</a></span>";
    }
}
if(isset($_GET['logout_status'])){
    if($_GET['logout_status'] =='yes'){
        echo "<br><span class=\"successmessage\">Logout succeeded <a href=\"./index.php?page=login_form\">Refresh page</a></span>";
    }

    elseif($_GET['logout_status'] =='no'){
        echo "<br><span class=\"errormessage\">Logout failed <a href=\"./index.php?page=login_form\">Refresh page</a></span>";
    }         
}


?>