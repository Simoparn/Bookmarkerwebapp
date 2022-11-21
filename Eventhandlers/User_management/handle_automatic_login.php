<?php

require_once('Eventhandlers/connect_database.php');

try{
//If the window is opened again, trying automatic login with Remember me and authentication token cookies
    if(isset($_COOKIE['rememberme']) && isset($_COOKIE['authentication_token'])){
        if(isset($_SESSION["previously_logged_out"])){
            unset($_SESSION["previously_logged_out"]);
        }
        $selector_and_validator=explode('.',$_COOKIE['authentication_token']);
        $selector=$selector_and_validator[0];
        $token_user_query=$connection->prepare("SELECT username, first_name, surname, phone_number, email, address_id, password_hash, is_staff, creation_date, last_modified FROM userprofile WHERE username= (SELECT username FROM usertoken WHERE selector=?)");
        $token_user_query->bind_param("s",$selector);
        if ($token_user_query->execute()){
            $token_user_query->store_result();
            $token_user_query->bind_result($username);
            while($token_user_query->fetch()){
                $user_address_query=$connection->prepare("SELECT address, postalcode, municipality, country, province, state FROM address WHERE address_id=?");
                $user_address_query->bind_result("i", $address_id);
                if($user_address_query->execute()){
                    $user_address_query->store_result();
                    if($username){
                        session_start();
                        $_SESSION['username']=$username;
                        $_SESSION['first_name']=$first_name;
                        $_SESSION['surname']=$surname;
                        $_SESSION['phone_number']=$phone_number;
                        $_SESSION['email']=$email;
                        $_SESSION['staff_status']=$staff_status;
                        $_SESSION['creation_date']=$creation_date;
                        $_SESSION['last_modified']=$last_modified;
                        $_SESSION['address']=$address;
                        $_SESSION['postal_code']=$postalcode;
                        $_SESSION['municipality']=$municipality;
                        $_SESSION['country']=$country;
                        $_SESSION['province']=$province;
                        $_SESSION['state']=$state;
                        //Redirect to frontpage if the right credentials found           
                        header('Location: ./index.php?page=frontpage&automatic_login_status=yes');
                        exit();
                    }
                    $user_address_query->free_result();
                }
            }
            //If the right credentials are not found, redirected to frontpage
            header('Location: ./index.php?page=frontpage&automatic_login_status=no');
            $token_user_query->free_result();
        }
    }
    else{
        //If tokens are not set.
        header('Location: ./index.php?page=login_form&automatic_login_status=no');
    }
                
}catch(Exception $e){
    //database error
    header('Location: ./index.php?page=login_form&automatic_login_status=unknown_error');
}


?>