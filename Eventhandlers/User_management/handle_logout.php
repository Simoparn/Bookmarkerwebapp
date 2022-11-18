<?php





session_start();
if(isset($_GET["logout"])){
    
    // Remove  Remember me and authentication token cookies
    if (isset($_COOKIE['rememberme'])) {
        unset($_COOKIE['rememberme']);
        setcookie('rememberme', null, -1000);
    

    // Remove Remember me and authentication token cookies
    if (isset($_COOKIE['authentication_token'])) {
        unset($_COOKIE['authentication_token']);
        setcookie('authentication_token', null, -1000);
    }
    //require_once('delete_authentication_token.php');  
}
    

    
    
    unset($_SESSION["username"]);
    session_destroy();
    session_start();
    $_SESSION["logged_out"]="yes";
    header('Location: ../../index.php?page=frontpage&logout_status=yes');
    
}
else{
    header('Location: ../../index.php?page=frontpage&logout_status=no');
}

?>