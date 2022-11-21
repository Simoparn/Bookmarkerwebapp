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
        
        
        
        $database_query->prepare("SELECT username, first_name, surname, phone_number, email, address_id, password_hash, is_staff, creation_date, last_modified FROM userprofile WHERE username=? OR email=?");
        $database_query->bind_param("ss",$given_username_or_email, $given_username_or_email);
        if ($database_query->execute()){
            $database_query->store_result();
            //echo "Database query executed.";
            //exit();
            $database_query->bind_result($username, $first_name, $surname, $phone_number, $email, $address_id, $password_hash, $staff_status, $creation_date, $last_modified);
            while($database_query->fetch()){
                $user_address_query=$connection->prepare("SELECT address, postalcode, municipality, country, province, state FROM address WHERE address_id=?");
                $user_address_query->bind_param("i",$address_id);
                if($user_address_query->execute()){
                    $user_address_query->store_result();
                    $user_address_query->bind_result($address, $postalcode, $municipality, $country, $province, $state);
                    while($user_address_query->fetch()){
                        //echo "Login database query results:".$username." ". $password_hash;
                        //exit();
                        $password_correct=password_verify($given_password, $password_hash);
                            
                
                        if(($given_username_or_email==$username || $given_username_or_email==$email) && $password_correct==true){
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
                    $user_address_query->free_result();
                }
                
        
                //If the right credentials were not found, redirected back
                header('Location: ../../index.php?page=login_form&login_status=no');
            }  
            $database_query->free_result();
            
        }
    }            
}catch(Exception $e){
    //database error
    echo $e;
    exit();
    header('Location: ../../index.php?page=login_form&login_status=unknown_error');
}



?>