<?php
//TODO: Problem with logout, redirects to XAMPP dashboard and can't reopen the site until cookie is expired
require dirname(dirname((__DIR__))).'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
$DOTENVDATA=$dotenv->load();

require_once('../connect_database.php');

//echo "CURRENT DIRECTORY:".__DIR__;
//exit();

$username=$_SESSION["username"];

$delete_authentication_token_query=$connection->prepare("DELETE FROM usertoken WHERE username=?");
$delete_authentication_token_query->bind_param("s",$username);

try{
    if($delete_authentication_token_query->execute()){
        //after deleting from database, remove the cookies from browser as well
        if(isset($_COOKIE['rememberme'])){
            unset($_COOKIE['rememberme']);
            setcookie('rememberme', null, -1);
        }
        if(isset($_COOKIE['authentication_token'])){
            unset($_COOKIE['authentication_token']);
            setcookie('authentication_token', null, -1);
        } 

        header('Location: ../../index.php?page=frontpage&logout_status=yes');
    }
   
    

}catch(Exception $e){
    //database error
    //echo $e;
    //exit();
    header('Location: ../../index.php?page=frontpage&database_error=yes');
}



?>