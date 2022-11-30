<?php
if(isset($_SESSION["username"])){
    $username=$_SESSION["username"];
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));
    $validator_hash = password_hash($validator, PASSWORD_DEFAULT);
    //$current_date=date_format(date_create(), 'Y-m-d H:i:s');
    //$expiration_date=date_add(date_interval_create_from_date_string("5 minutes"),$current_date);
    //echo "$current_date";
    //echo "<br>$expiration_date";
    //exit();
    $database_query->prepare("INSERT INTO usertoken (selector,validator_hash,username,expiration_date) VALUES (?,?,?, NOW()+ INTERVAL 2 MINUTE)");
    $database_query->bind_param("sss",$selector,$validator_hash,$username);
    try{
        if($database_query->execute()){
                    //Redirected if authentication token save succeeded
                    setcookie("rememberme","rememberme",time()+120,"/");
                    setcookie("authentication_token",$selector.".".$validator,time()+120,"/");
                    header('Location: ../../index.php?page=frontpage&create_authentication_token_status=yes');
                    exit();
        }
        else{
            header('Location: ../../index.php?page=frontpage&create_authentication_token_status=no');
                    
        }
    } 
    catch(Exception $e){
        //database error
        header('Location: ../../index.php?page=frontpage&database_error=yes');
    }

}
?>