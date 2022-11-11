<?php





session_start();
if(isset($_GET["logout"]) == "yes"){
    $_SESSION["username"]=$_POST["username"];
    $_SESSION["password"]=$_POST["password"];
    
    // Remove  Remember me and authentication token cookies
    if (isset($_COOKIE['rememberme'])) {
        unset($_COOKIE['rememberme']);
        setcookie('rememberme', null, -1000);
    }

    // Remove Remember me and authentication token cookies
    if (isset($_COOKIE['authentication_token'])) {
        unset($_COOKIE['authentication_token']);
        setcookie('authentication_token', null, -1000);
    }
    

    
    
    
    
    session_destroy();
    unset($_SESSION["username"]);
    //Redirected back to frontpage
    //TODO: to the same page?
    header('Location: ../../index.php?page=frontpage&logout_status=yes');
    
}
else{
    //Redirected back to frontpage
    //TODO: to the same page?
    header('Location: ../../index.php?page=frontpage&logout_status=no');
}

?>