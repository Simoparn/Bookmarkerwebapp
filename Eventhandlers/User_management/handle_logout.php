<?php
//TODO: unsetting $_COOKIE variables doesn't work here for some reason, only in check_authentication_token.php
if(isset($_GET["logout"])){
    session_start();
    // Remove  Remember me and authentication token cookies
    if (isset($_COOKIE['rememberme'])) {
        setcookie('rememberme', null, -1);
        unset($_COOKIE['rememberme']);
    

    // Remove Remember me and authentication token cookies
    if (isset($_COOKIE['authentication_token'])) {
        setcookie('authentication_token', null, -1);
        unset($_COOKIE['authentication_token']);
    }
    //require_once('delete_authentication_token.php');  
}
    

    
    
    unset($_SESSION["username"]);
    session_destroy();
    session_start();
    $_SESSION["previously_logged_out"]="yes";
    header('Location: ../../index.php?page=frontpage&logout_status=yes');
    
}
else{
    header('Location: ../../index.php?page=frontpage&logout_status=no');
}

?>