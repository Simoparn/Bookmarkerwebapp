<?php

if(!isset($_SESSION["username"]) && !isset($_COOKIE["authentication_token"])){
    //Remove old tokens from the database
    $remove_old_authentication_token_query=$connection->prepare("DELETE FROM usertoken WHERE expiration_date < NOW()");
    try{
        $remove_old_authentication_token_query->execute();
            
    }catch(Exception $e){
        //database_error
        header('Location: ../../index.php?page=frontpage&database_error=yes');
    }
}






?>