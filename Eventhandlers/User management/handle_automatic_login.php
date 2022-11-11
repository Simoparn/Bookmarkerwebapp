<?php

require_once('Eventhandlers/connect_database.php');

try{
//If the window is opened again, trying automaatic login with Remember me and authentication token cookies
    if(isset($_COOKIE['rememberme']) && isset($_COOKIE['authentication_token'])){
        $selector_and_validator=explode('.',$_COOKIE['authentication_token']);
        $selector=$selector_and_validator[0];
        $token_user_query=$connection->prepare("SELECT username FROM userprofile WHERE username= (SELECT username FROM usertoken WHERE selector=?)");
        $token_user_query->bind_param("s",$selector);
        if ($token_user_query->execute()){
            $token_user_query->bind_result($username);
            while($token_user_query->fetch()){
                if($username){
                    session_start();
                    $_SESSION['username']=$username;
                    //Redirect to frontpage if the right credentials found           
                    header('Location: ./index.php?page=frontpage&automatic_login_status=yes');
                    exit();
                }
            }
            //If the right credentials are not found, redirected to frontpage
            header('Location: ./index.php?page=frontpage&automatic_login_status=no');
        }
    }
    else{
        //If isername and password are not set. This is not needed in principle, required attribute in login form protects from this case
        header('Location: ./index.php?page=login_form&automatic_login_status=no');
    }
                
}catch(Exception $e){
    //database error
    header('Location: ./index.php?page=login_form&login_status=unknown_error');
}


?>