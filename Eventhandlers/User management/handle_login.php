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
    if(isset($_POST["username"]) && isset($_POST["password"])){
        //$email_regex="/^.{16,}";
        $given_username=$_POST["username"];
        $given_password=$_POST["password"];
        
        
        
        $database_query->prepare("SELECT username, password_hash FROM userprofile WHERE username=?");
        $database_query->bind_param("s",$given_username);
        if ($database_query->execute()){

            $database_query->bind_result($username, $password_hash);
            while($database_query->fetch()){
                //echo "Login database query results:".$username." ". $password_hash;
                //exit();
                $actual_password=password_verify($given_password, $password_hash);
                            
                
                if($given_username==$username && $actual_password==true){
                    //TODO: notification message that the session is already active, not needed?
                    //session_start();
                    $_SESSION['username']=$username;
                    
                    if(isset($_POST["rememberme"])){
                        require_once('create_authentication_token.php');
                    }
                

                    //Redirected to frontpage if the right credentials were found           
                    header('Location: ../../index.php?page=frontpage&login_status=no');
                    exit();
                }
            }
                
        
                //If the right credentials were not found, redirected back
                header('Location: ../../index.php?page=login_form&login_status=no');
                
        
        }
    }
               
}catch(Exception $e){
    //database error
    //echo $e;
    //exit();
    header('Location: ../../index.php?page=login_form&login_status=unknown_error');
}



?>