<?php

require_once('Eventhandlers/connect_database.php');


/* check the remember_me in cookie
$token = filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING);

if ($token && token_is_valid($token)) {

    $user = find_user_by_token($token);

    if ($user) {
        return log_user_in($user);
    }
}*/

if(!isset($_SESSION["username"]) && isset($_COOKIE["authentication_token"])){
    $authentication_token=filter_input(INPUT_COOKIE,"authentication_token",FILTER_SANITIZE_STRING);
    $selector_and_validator=explode(".",$authentication_token);
    $selector=$selector_and_validator[0];
    $validator=$selector_and_validator[1];
    
    $check_authentication_token_query=$connection->prepare("SELECT username, selector, validator_hash, expiration_date FROM usertoken WHERE selector = ? and expiration_date > NOW()");
    $check_authentication_token_query->bind_param("s",$selector);
    try{
        if($check_authentication_token_query->execute()){
            $check_authentication_token_query->store_result();
            $check_authentication_token_query->bind_result($username,$selector,$validator_hash,$expiration_date);
            while($check_authentication_token_query->fetch()){
                echo $username;
                if(password_verify($validator,$validator_hash)==true){
                    $_SESSION["username"]=$username;
                    //Redirected if the right token was found
                    require_once('Eventhandlers/User_management/handle_automatic_login.php');
                    exit();
                }
            }
            $check_authentication_token_query->free_result();
            //If an active authentication token is not found from the database when a token cookie is in browser, 
            //remove the cookies from browser, database removal can be handled with subsequent non-cookie page openings
            if(isset($_COOKIE['rememberme'])){
                setcookie('rememberme', null, -1);
                unset($_COOKIE['rememberme']);
            }
            if(isset($_COOKIE['authentication_token'])){
                setcookie('authentication_token', null, -1);
                unset($_COOKIE['authentication_token']);
            }

            

            header('Location: ../../index.php?page=frontpage&automatic_login_status=no');
        }
       
        

    }catch(Exception $e){
        //database error
        header('Location: ../../index.php?page=frontpage&database_error=yes');
    }

}







?>