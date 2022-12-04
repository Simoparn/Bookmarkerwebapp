<?php

require dirname(dirname((__DIR__))).'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
$DOTENVDATA=$dotenv->load();



if(isset($_POST["delete_user"])){
    $username_from_post=substr_replace($_POST["delete_user"],"",0,strlen("DELETE USER "));
    //echo "only username:".$username_from_post;
    //exit();

    try{

        require_once('../connect_database.php');

        $check_if_only_one_user_for_address_query=$connection->prepare("SELECT COUNT(address_id), address_id FROM userprofile WHERE address_id=(SELECT address_id FROM userprofile WHERE username=?)");
        $check_if_only_one_user_for_address_query->bind_param("s",$username_from_post);
        if($check_if_only_one_user_for_address_query->execute()){
            $check_if_only_one_user_for_address_query->store_result();
            $check_if_only_one_user_for_address_query->bind_result($address_count,$address_id);
            $check_if_only_one_user_for_address_query->fetch();
            $delete_user_query=$connection->prepare("DELETE FROM userprofile WHERE username=?");
            $delete_user_query->bind_param("s",$username_from_post);
            if ($delete_user_query->execute()){
                
                $delete_user_query->store_result();
                //echo "Database query executed.";
                //exit();

                //The address must be deleted if no users are left for it
                if($address_count == 1){
                    $delete_useless_address_query=$connection->prepare("DELETE FROM address WHERE address_id=?");
                    $delete_useless_address_query->bind_param("i", $address_id);
                    if($delete_useless_address_query->execute()){

                        $delete_useless_address_query->store_result();           
                            header('Location: ../../index.php?page=user_list&user_deleted_status=yes');
                            exit();
                        $delete_useless_address_query->free_result();
                        }
                }
                else{
                    header('Location: ../../index.php?page=user_list&user_deleted_status=yes');
                    exit();
                }
                        $delete_user_query->free_result();
            }
                    
    
            
            $check_if_only_one_user_for_address_query->free_result();
        }

    }catch(Exception $e){
            //database error
            //echo $e;
            //exit();
            header('Location: ../../index.php?page=user_list&user_deleted_status=no');
    }
}


?>