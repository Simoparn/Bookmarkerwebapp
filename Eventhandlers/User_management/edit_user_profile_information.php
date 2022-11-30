<?php
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
        $_SESSION["province"]=$_POST["province"];
        $_SESSION["state"]=$_POST["state"];
        header('Location: ../../index.php?page=user_profile&user_information_edited_status=yes');
        exit();
    }
    else{
        header('Location: ../../index.php?page=user_profile&user_information_edited_status=no');
        exit();
    }

?>