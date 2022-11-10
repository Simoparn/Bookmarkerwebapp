<?php

if(isset($_GET["login_status"])){  
    if($_GET["login_status"] =="yes"){
        echo "<br><span class=\"successmessage\">Login succeeded <a href=\"./index.php?page=frontpage\">Refresh page</a></span>";
    }
}



if(isset($_GET["automatic_login_status"])){  
    if($_GET["automatic_login_status"] =="yes"){
        echo "<br><span class=\"successmessage\">Remember me option is active, automatic user login succeeded<a href=\"./index.php?page=frontpage\">Refresh page</a></span>";
    }
    if($_GET["automatic_login_status"] =="no"){
        echo "<br><span class=\"errormessage\">Remember me option is active, automatic user login failed, authentication token has expired <a href=\"./index.php?page=frontpage\">Refresh page</a></span>";
    }
}
if(isset($_GET['logout_status'])){
    if($_GET['logout_status']=='yes'){
        echo "<br><span class=\"successmessage\">Logout succeeded <a href=\"./index.php?page=frontpage\">Refresh page</a></span>";
    }

    elseif($_GET['logout_status']=='no'){
        echo "<br><span class=\"errormessage\">Logout failed <a href=\"./index.php?page=frontpage\">Refresh page</a></span>";
    }
}
if(isset($_GET['create_authentication_token_status'])){
    if($_GET['create_authentication_token_status']=='yes'){
        echo "<br><span class=\"successmessage\">\"Remember me\" token creation succeeded <a href=\"./index.php?page=frontpage\">Refresh page</a></span>";
    }

    elseif($_GET['create_authentication_token_status']=='no'){
        echo "<br><span class=\"errormessage\">\"Remember me\" token creation failed <a href=\"./index.php?page=frontpage\">Refresh page</a></span>";
    }
}

if(isset($_GET['database_error'])){
    echo "<br><span class=\"errormessage\">Database error, automatic login or removing outdated authentications failed <a href=\"./index.php?page=frontpage\">Refresh page</a></span>";
}


?>