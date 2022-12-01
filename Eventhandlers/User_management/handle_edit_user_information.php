<?php

require dirname(dirname((__DIR__))).'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
$DOTENVDATA=$dotenv->load();

if(isset($_POST['edit_user_information'])){

    
$address_already_exists=false;
//$old_address_id=false;
    try{
        

        require_once('../connect_database.php');

        $check_if_address_exists_query=$connection->prepare("SELECT address, address_id FROM address WHERE address=? AND postalcode=? AND municipality=? AND country=? AND province=? AND state=?");
        $check_if_address_exists_query->bind_param("ssssss",$_POST['address'], $_POST['postal_code'],$_POST['municipality'], $_POST['country'], $_POST['province'], $_POST['state']);
       
        
        
        if($check_if_address_exists_query->execute()){
            $check_if_address_exists_query->store_result();
            $check_if_address_exists_query->bind_result($address, $address_id);
            while($check_if_address_exists_query->fetch()){
                        
                    $address_already_exists=true;
                    break;
                
                
            }

            if($address_already_exists == false){
                //echo "Address doesn't exist!";
                //exit();
                $check_if_only_one_user_for_address_query=$connection->prepare("SELECT COUNT(address_id), address_id FROM userprofile WHERE address_id=(SELECT address_id FROM userprofile WHERE username=?)");
                $check_if_only_one_user_for_address_query->bind_param("s",$_SESSION["username"]);
                if($check_if_only_one_user_for_address_query->execute()){

                    $check_if_only_one_user_for_address_query->store_result();
                    $check_if_only_one_user_for_address_query->bind_result($number_of_users_for_address, $old_address_id);
                    $check_if_only_one_user_for_address_query->fetch();
                    if($number_of_users_for_address==1){
                        //echo "only one user, old address deletion required after updating user to new address!";
                        //exit();                      
                        
                            require_once('create_new_address.php');
                            if($create_new_address_query->execute()){

                                $edit_user_information_query=$connection->prepare("UPDATE userprofile SET first_name=?, surname=?, phone_number=?, email=?, address_id=(SELECT MAX(address_id) FROM address) WHERE username=?");
                                $edit_user_information_query->bind_param("sssss", $_POST["first_name"], $_POST["surname"], $_POST["phone_number"], $_POST["email"], $_SESSION["username"]);
                                if($edit_user_information_query->execute()){
                                    
                                    $delete_old_address_query=$connection->prepare("DELETE FROM address WHERE address_id=?");
                                    $delete_old_address_query->bind_param("i",$old_address_id);
                                    if($delete_old_address_query->execute()){

                                        //echo "Deleting orphan address succeeded!";
                                        $_SESSION["first_name"]=$_POST["first_name"];
                                        $_SESSION["surname"]=$_POST["surname"];
                                        $_SESSION["phone_number"]=$_POST["phone_number"];
                                        $_SESSION["email"]=$_POST["email"];
                                        $_SESSION["address"]=$_POST["address"];
                                        $_SESSION["postal_code"]=$_POST["postal_code"];
                                        $_SESSION["municipality"]=$_POST["municipality"];
                                        $_SESSION['country']=$_POST['country'];
                                        $_SESSION["province"]=$_POST["province"];
                                        $_SESSION["state"]=$_POST["state"];
                                        header('Location: ../../index.php?page=user_profile&user_information_edited_status=yes');
                                        exit();
                                    }   
                                    else{
                                        //echo "Deleting orphan address failed!";
                                        header('Location: ../../index.php?page=user_profile&user_information_edited_status=no');
                                        exit();
                                    }
                                }

                                
                                
                            }

                        //}
                    }
                    else{
                        //echo "several users!".$number_of_users_for_address;
                        //exit();
                        require_once('create_new_address.php');
                        if($create_new_address_query->execute()){
                            $edit_user_information_query=$connection->prepare("UPDATE userprofile SET first_name=?, surname=?, phone_number=?, email=?, address_id=(SELECT MAX(address_id) FROM address) WHERE username=?");
                                $edit_user_information_query->bind_param("sssss", $_POST["first_name"], $_POST["surname"], $_POST["phone_number"], $_POST["email"], $_SESSION["username"]);
                                if($edit_user_information_query->execute()){

                                    $_SESSION["first_name"]=$_POST["first_name"];
                                    $_SESSION["surname"]=$_POST["surname"];
                                    $_SESSION["phone_number"]=$_POST["phone_number"];
                                    $_SESSION["email"]=$_POST["email"];
                                    $_SESSION["address"]=$_POST["address"];
                                    $_SESSION["postal_code"]=$_POST["postal_code"];
                                    $_SESSION["municipality"]=$_POST["municipality"];
                                    $_SESSION['country']=$_POST['country'];
                                    $_SESSION["province"]=$_POST["province"];
                                    $_SESSION["state"]=$_POST["state"];
                                    header('Location: ../../index.php?page=user_profile&user_information_edited_status=yes');
                                    exit();

                                }
                                else{
                                    //echo "Deleting orphan address failed!";
                                    header('Location: ../../index.php?page=user_profile&user_information_edited_status=no');
                                    exit();  
                                    
                                }
                        }
                        
                    }
                    $check_if_only_one_user_for_address_query->free_result();
                
                }
            }else{
                //echo "Address exists!";
                //exit();
                $edit_user_information_query=$connection->prepare("UPDATE userprofile SET first_name=?, surname=?, phone_number=?, email=?, address_id=(SELECT MAX(address_id) FROM address) WHERE username=?");
                $edit_user_information_query->bind_param("sssss", $_POST["first_name"], $_POST["surname"], $_POST["phone_number"], $_POST["email"], $_SESSION["username"]);
                
                    if($edit_user_information_query->execute()){
                            
                        $_SESSION["first_name"]=$_POST["first_name"];
                        $_SESSION["surname"]=$_POST["surname"];
                        $_SESSION["phone_number"]=$_POST["phone_number"];
                        $_SESSION["email"]=$_POST["email"];
                        header('Location: ../../index.php?page=user_profile&user_information_edited_status=yes');
                        exit();
                            
                    }
                    else{
                        header('Location: ../../index.php?page=user_profile&user_information_edited_status=no');
                    }
            }

            $check_if_address_exists_query->free_result();
        }

    }catch(Exception $e){
        echo "Database error: ".$e;  
        exit();             
        header('Location: ../../index.php?page=user_profile&database_error=yes');
        exit();
    }



    


}



?>