<?php

    
require dirname(dirname((__DIR__))).'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
$DOTENVDATA=$dotenv->load();
    
//foreach($DOTENVDATA as $key => $value){
//    echo $key.' '.$value;
//}
//exit();

try{


    require_once('../connect_database.php');
    
    //If logged in normally
    if(isset($_POST["username_or_email"]) && isset($_POST["password"])){
        if(isset($_SESSION["previously_logged_out"])){
            unset($_SESSION["previously_logged_out"]);
        }
        //$email_regex="/^.{16,}";
        $given_username_or_email=$_POST["username_or_email"];
        $given_password=$_POST["password"];
        
        
        
        $database_query->prepare("SELECT username, email, password_hash FROM userprofile WHERE username=? OR email=?");
        $database_query->bind_param("ss",$given_username_or_email, $given_username_or_email);
        if ($database_query->execute()){
            //echo "Database query executed.";
            //exit();
            $database_query->bind_result($username, $email, $password_hash);
            while($database_query->fetch()){
                //echo "Login database query results:".$username." ". $password_hash;
                //exit();
                $password_correct=password_verify($given_password, $password_hash);
                            
                
                if(($given_username_or_email==$username || $given_username_or_email==$email) && $password_correct==true){
                    $_SESSION['username']=$username;
                    //echo "Correct username and password!";
                    //exit();
                    if(isset($_POST["rememberme"])){
                        require_once('create_authentication_token.php');
                    }
                

                    //Redirected to frontpage if the right credentials were found           
                    header('Location: ../../index.php?page=frontpage&login_status=yes');
                    exit();
                }
            }
                
        
                //If the right credentials were not found, redirected back
                header('Location: ../../index.php?page=login_form&login_status=no');
                
        
        }
    }
               
}catch(Exception $e){
    //database error
    echo $e;
    exit();
    header('Location: ../../index.php?page=login_form&login_status=unknown_error');
}



?>