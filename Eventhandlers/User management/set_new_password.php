<?php
require_once('../connect_database.php');
//TODO: Session already started warning, not needed here?
//session_start();
$given_email=$_POST["email"];
$new_password=$_POST["new_password"];
$given_password_confirm=$_POST["confirm_new_password"];



$get_user_emails_query=$connection->prepare("SELECT sahkoposti FROM kayttajatili");
$new_password_query=$connection->prepare("UPDATE kayttajatili SET password_hash=? WHERE sahkoposti=?");


try{
    if($get_user_emails_query->execute()){
        $get_user_emails_query->store_result();
        $get_user_emails_query->bind_result($email);
        while($get_user_emails_query->fetch()){
            if(password_verify($given_email, $email_hash)){
                if($new_password == $given_password_confirm){
                    $password_hash=password_hash($new_password, PASSWORD_DEFAULT);
                    //update if both the email given in the change form and the new passwords are correct
                    $new_password_query->bind_param("ss",$password_hash,$email);
                    if($new_password_query->execute()){
                        header('Location: ../../index.php?page=set_new_password_form&password_change_status=yes&correct_email=yes');
                        exit();
                    }
                }
                else{
                    //If the new password and new password confirm dont match 

                    header('Location: ../../index.php?page=set_new_password_form&password_change_status=no&correct_email=yes');
                    exit();
                    
                }
            }
        }
        $get_user_emails_query->free_result();
        
            header('Location: ../../index.php?page=set_new_password_form&password_change_status=no&correct_email=no');
    }
        
    
}catch(Exception $e){
    echo "Database error: ".$e;  
    exit();             
    header('Location: ../../index.php?page=set_new_password_form&password_change_status=no&database_error=yes');
    exit();
}
?>