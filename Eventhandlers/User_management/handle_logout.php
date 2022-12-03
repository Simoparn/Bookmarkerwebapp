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
    unset($_SESSION['first_name']);
    unset($_SESSION['surname']);
    unset($_SESSION['phone_number']);
    unset($_SESSION['email']);
    unset($_SESSION['staff_status']);
    unset($_SESSION['creation_date']);
    unset($_SESSION['last_modified']);
    unset($_SESSION['address']);
    unset($_SESSION['postal_code']);
    unset($_SESSION['municipality']);
    unset($_SESSION['country']);
    unset($_SESSION['province']);
    unset($_SESSION['state']);
    session_destroy();
    //session_start needed here for previously_logged_out
    session_start();
    $_SESSION["previously_logged_out"]="yes";
    header('Location: ../../index.php?page=frontpage&logout_status=yes');
    
}
else{
    header('Location: ../../index.php?page=frontpage&logout_status=no');
}

?>