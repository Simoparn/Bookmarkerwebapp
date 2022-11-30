<?php


require dirname(dirname((__DIR__))).'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
$DOTENVDATA=$dotenv->load();

require_once('../connect_database.php');




if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])){
    if($_POST["confirm_password"]==$_POST["password"]){
        //foreach($_POST as $key => $value){
        //echo ' '.$value;
        //}
        $given_first_name=$_POST['first_name'];
        $given_surname=$_POST['surname'];
        $given_phone_number=$_POST['phone_number'];
        $given_email=strtolower(trim($_POST['email']));
        $given_address=$_POST['address'];
        $given_postal_code=$_POST['postal_code'];
        $given_municipality=$_POST['municipality'];
        $given_country=$_POST['country'];
        $given_province=$_POST['province'];
        $given_state=$_POST['state'];
        $given_username=$_POST['username'];
        $given_password=$_POST['password'];
        $given_password_hash=password_hash($given_password, PASSWORD_DEFAULT);
        
        $user_already_exists=false;
        $address_id="";
        $address_already_exists=false;

        
        $user_already_exists_query=$connection->prepare("SELECT username, email FROM userprofile WHERE username=? OR email=?");
        $user_already_exists_query->bind_param("ss",$given_username, $given_email);

        if($user_already_exists_query->execute()){
            $user_already_exists_query->store_result();
            while($user_already_exists_query->fetch()){
                $user_already_exists_query->bind_result($username,$email);
                   
                
                if($given_username==$username || $given_email==$email){
                //Redirect with a failure if the credentials or the email already exist
  
                    header('Location: ../../index.php?page=registration_form&registration_status=user_already_exists');
                    $user_already_exists=true;
                    
                    break;
                    
                }
                
                
            }
            if($user_already_exists==false){
                //echo '<br>Given user:'.$given_username.' Retrieved user: '.$retrieved_user.' password already exists:'.$password_already_exists' lines in the user query loop:'.$lines;
                
                //Address must be created first because the user table has a foreign key pointing to the address table
                //First check that the given address exists
                $address_query=$connection->prepare("SELECT address_id, address FROM address WHERE address=?");
                $address_query->bind_param("s",$given_address);
                if($address_query->execute()){
                    $address_query->bind_result($address_id, $address);
                    while($address_query->fetch()){
                        
                        echo $address_id.' '.$address;
                        if($address==$given_address){
                            $address_id=$address_id;
                            $address_already_exists=true;
                            break;
                        }
                    }
                }
                if($address_already_exists==false){
                    
                    //If state is empty, separate query
                    if($given_state=="" || $given_state==NULL){
                        $set_address_query=$connection->prepare("INSERT INTO address (address,postalcode,municipality,country,province,state) VALUES(?,?,?,?,?,NULL)");
                        $set_address_query->bind_param("sssss",$given_address,$given_postal_code,$given_municipality,$given_country,$given_province);
                    }
                    else{
                        $set_address_query=$connection->prepare("INSERT INTO address (address,postalcode,municipality,country,province,state) VALUES(?,?,?,?,?,?)"); 
                        $set_address_query->bind_param("ssssss",$given_address,$given_postal_code,$given_municipality,$given_country,$given_province,$given_state);
                    }
                    if($set_address_query->execute()){                  
                            $set_address_query->store_result();
                            //If setting address succeeded, retrieve the id of the new address, create the user account and redirect with a success
                            $get_address_id_query=$connection->prepare("SELECT MAX(address_id) FROM address");
                            if($get_address_id_query->execute()){
                                $get_address_id_query->store_result();
                                $get_address_id_query->bind_result($address_id);
                                while($get_address_id_query->fetch()){
                                    echo '<br>'.$address_id; 
                                    $current_date=(string)(date_format(date_create(), 'Y-m-d H:i:s'));
                                    //TODO: Need checks for defining user as staff, if user creation is attempted when logged in as an admin
                                    $create_user_query=$connection->prepare("INSERT INTO userprofile VALUES (?,?,?,?,?,?,?,TRUE,FALSE,?,NULL)");
                                    $create_user_query->bind_param("ssssssid",$given_username,$given_password_hash,$given_first_name,$given_surname,$given_phone_number,$given_email,$address_id,$current_date);
                                }
                                try{
                                    if($create_user_query->execute()){
                                        header('Location: ../../index.php?page=registration_form&registration_status=yes');
                                    }
                                }catch(Exception $e){
                                    echo $e;
                                    exit();
                                    header('Location: ../../index.php?page=registration_form&registration_status=no');
                                }
                                $get_address_id_query->free_result();
                            }
                            $set_address_query->free_result();
                    }
                }
                else{
                    $current_date=(string)(date_format(date_create(), 'Y-m-d H:i:s'));
                    //TODO: Need checks for defining user as staff, if user creation is attempted when logged in as an admin
                    $create_user_query=$connection->prepare("INSERT INTO userprofile VALUES (?,?,?,?,?,?,?,TRUE,FALSE,?,NULL)");
                    $create_user_query->bind_param("ssssssid",$given_username,$given_password_hash,$given_first_name,$given_surname,$given_phone_number,$given_email,$address_id,$current_date);
                    try{
                        if($create_user_query->execute()){
                            
                            header('Location: ../../index.php?page=registration_form&registration_status=yes');
                            
                        }
                    }catch(Exception $e){
                        header('Location: ../../index.php?page=registration_form&registration_status=no');
                    }
                }
            }
              
            else{
                //Redirected with a failure if the user already exists
                header('Location: ../../index.php?page=registration_form&registration_status=user_already_exists');             
            }          
        
            $user_already_exists_query->free_result();
        }       
        else {
            echo "Database error: username retrieval failed ".$connection->error;
        }
    }
    else {
        //password and password confirm do not match
        header('Location: ../../index.php?page=registration_form&registration_status=passwords_dont_match');
    }
          
}
else{
    header('Location: ../../index.php?page=registration_form&registration_status=no');
}
    
?>