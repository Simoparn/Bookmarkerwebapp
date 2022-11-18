<?php




if(isset($_GET['email'])){
    $link_email_hash=$_GET['email'];
}
if(isset($_GET['username'])){
    $link_username_hash=$_GET['username'];
}



$database_query->prepare("SELECT email, username FROM userprofile");
if($database_query->execute()){
    $database_query->bind_result($email, $username);
    while($database_query->fetch()){
        echo '<br>'.$email;
        echo '<br>'.$username;
        echo '<br> given email as hash: '.$link_email_hash;
        echo '<br> given username as hash: '.$link_username_hash;
        echo '<br> email corresponds to hash: '.password_verify($email, $link_email_hash);
        echo '<br>username corresponds to hash: '.password_verify($username, $link_username_hash);
        if(password_verify($email,$link_email_hash) && password_verify($username, $link_username_hash)){
            
            //Referring to the current directory, because the event handler is used directly in index.php:ssa and not as the form action graphical component
            //header('Location: ./index.php?page=set_new_password_form&change_password_link_open_status=yes&username_hash='.$link_username_hash);
            $_SESSION["change_password_link_open_status"]=true;
            $_SESSION["old_password_email"]=$email;
            header('Location: ./index.php?page=set_new_password_form');
            exit();
        }
       
        
    
    }
    
    $_SESSION["change_password_link_open_status"]=false;
    
    
}
else{
    echo "Database error when opening password change link: ".$connection->error;
    $_SESSION["change_password_link_open_status"]=false;
    header('Location: ./index.php?page=set_new_password_form&database_error=yes');
}








?>