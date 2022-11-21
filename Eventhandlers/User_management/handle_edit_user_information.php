<?php

if(isset($_POST['edit_user_information'])){
    
    
$address_already_exists=false;

    try{
        
        $check_if_address_exists_query=$connection->prepare("SELECT address, address_id FROM address WHERE address=?");
        $check_if_address_exists_query->bind_param("s",$_POST['address']);
       
        
        
        if($check_if_address_exists_query->execute()){
            $check_if_address_exists_query->store_result();

            while($check_if_address_exists_query->fetch()){
                        
                if($address==$given_address){
                    $address_id=$address_id;
                    $address_already_exists=true;
                    break;
                }
                
            }
            if($address_already_exists==false){
                //If state is empty, separate query
                if($_POST["state"]=="" || $_POST["state"]==NULL){
                    $create_new_address_query=$connection->prepare("INSERT INTO address (address, postalcode, municipality, country, province, state) VALUES (?,?,?,?,?,?");
                    $create_new_address_query->bind_param("ssssss",$_POST["address"], $_POST["postal_code"],$_POST["municipality"],$_POST["country"],$_POST["province"],$_POST["state"]);
                }
                else{
                    $create_new_address_query=$connection->prepare("INSERT INTO address (address, postalcode, municipality, country, province, state) VALUES (?,?,?,?,?,NULL");
                    $create_new_address_query->bind_param("sssss",$_POST["address"], $_POST["postal_code"],$_POST["municipality"],$_POST["country"],$_POST["province"]);
                }
                if($create_new_address_query->execute()){
                    
                    $edit_user_information_query=$connection->prepare("UPDATE userprofile SET first_name=?, surname=?, phone_number=?, email=?, address_id=?");
                    $edit_user_information_query->bind_param("ssssi",$_POST["first_name"],$_POST["surname"],$_POST["phone_number"],$_POST["email"],$address_id);

                    if($edit_user_information_query->execute()){

                        $_SESSION["first_name"]=$_POST["first_name"];
                        $_SESSION["surname"]=$_POST["surname"];
                        $_SESSION["phone_number"]=$_POST["phone_number"];
                        $_SESSION["email"]=$_POST["email"];
                        $_SESSION["address"]=$_POST["address"];
                        $_SESSION["postalcode"]=$_POST["postalcode"];
                        $_SESSION["municipality"]=$_POST["municipality"];
                        $_SESSION["province"]=$_POST["province"];
                        $_SESSION["state"]=$_POST["state"];
                        header('Location: ./index.php?page=user_profile&user_information_edited_status=yes');
                        exit();
                    }
                    else{
                        header('Location: ./index.php?page=user_profile&user_information_edited_status=no');
                        exit();
                    }

                    
                    
                }
            }
            
            $check_if_address_exists_query->free_result();
        }

    }catch(Exception $e){
        //echo "Database error: ".$e;  
        //exit();             
        header('Location: ../../index.php?page=set_new_password_form&password_change_status=no&database_error=yes');
        exit();
    }



    


}



?>