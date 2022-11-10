<?php 
    require './vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $DOTENVDATA=$dotenv->load();
    //foreach($DOTENVDATA as $key => $value){
    //    echo $key.' '.$value;
    //}
    
    //foreach($_ENV as $key => $value){
    //   echo '<br>'.$key.' '.$value;
    //}

    //exit();
    
    require_once('Eventhandlers/connect_database.php');
    
    
    //Remember me- eväste
    if(isset($_COOKIE['rememberme'])){
        require_once('Eventhandlers/User management/check_authentication_token.php');
    }
    else{
        require_once('Eventhandlers/User management/remove_old_authentication_tokens.php');
    }
    
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
<?php
                     
    require_once('Graphics/header.php');
    
    //echo '<b>'.$_SERVER['PHP_SELF'].'</b>';
    //$currentdate=date_create();
    //echo (string)date_format($currentdate, 'Y-m-d H:i:s');    
?>

<body>
    <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>-->
<?php


    if(isset($_COOKIE['rememberme'])){
        echo "Remember me-cookie set, checking the authentication token";
    }
    /*echo "<h3> COOKIE variables:</h3>";
    foreach($_COOKIE as $key => $value){
        echo "<br><h5>$key : $value</h5>";
    }
    echo "<h3> SESSION variables:</h3>";
    foreach($_SESSION as $key => $value){
        echo "<br><h5>$key : $value</h5>";
    }*/
    
    require_once('Graphics/navigation_panel.php');
    if(isset($_GET['currentpage'])){
        switch($_GET['currentpage']){

            case 'categories':
                require_once('Graphics/Notifications/Notifications_frontpage.php');
                require_once('Graphics/frontpage.php');       
                break;
            
            case 'tuotteet':
                require_once('Graphics/Notifications/Notifications_categories.php');
                require_once('Graphics/Categories.php');
                break;
            
            case 'categorypage_1':
                require_once('Graphics/Notifications/Notifications_categorypage_1.php');
                require_once('Graphics/categorypage_1.php');
                break;
            
            case 'categorypage_2':
                require_once('Graphics/Notifications/Notifications_categorypage_2.php');
                require_once('Graphics/categorypage_2.php');
                break;
            
            case 'categorypage_3':
                require_once('Graphics/Notifications/Notifications_categorypage_3.php');
                require_once('Graphics/categorypage_3.php');
                break;
            
            case 'categorypage_4':
                require_once('Graphics/Notifications/Notifications_categorypage_4.php');
                require_once('Graphics/categorypage_4.php');
                break;
            
            case 'about_us':
                require_once('Graphics/Notifications/Notifications_about_us.php');
                require_once('Graphics/about_us.php');
                break;
            
            case 'contact':
                require_once('Graphics/Notifications/Notifications_contact.php');
                require_once('Graphics/contact.php');
                break;
            
            case 'login_form': 
                if(!isset($_SESSION['username'])){
                    require_once('Graphics/Notifications/login_form/login_and_logout.php');
                    require_once('Graphics/login_form.php');
                }
                else{
                    require_once('Graphics/Notifications/login_form/logged_in.php');
                }
                break;
            
            case 'forgotten_password_form':
                require_once('Graphics/Notifications/forgotten_password_form/logout_and_send_password.php');
                
                if(!isset($_SESSION['username'])){
                    require_once('Graphics/forgotten_password_form.php');
                    /*echo "<h1>forgotten_password_form, GET variables:</h1>";
                        foreach($_GET as $key => $value){
                            echo "<br><h2>$key : $value</h2>";
                        }*/
                }
                else{  
                     require_once('Graphics/Notifications/forgotten_password_form/logged_in.php');
                }
                break;
            
            case 'set_new_password_form':
                if(!isset($_SESSION['username'])){
                    if((isset($_GET['logout_status']) || isset($_GET['email']) || isset($_GET['username']) || isset($_SESSION["change_password_link_open_status"])  || isset($_GET['password_change_status']))){
                        require_once('Graphics/Notifications/set_new_password_form/logout.php');
                        
                        if(isset($_GET['email']) && isset($_GET['username'])){
                        
                            
                            require_once('Eventhandlers/User management/handle_password_change_link.php');
                        }
                        if(isset($_SESSION['change_password_link_open_status'])){                        
                            if($_SESSION['change_password_link_open_status']==true){
                                if(isset($_SESSION["old_password_email"])){
                                    require_once('Graphics/Notifications/set_new_password_form/change_password_link_open_status.php');
                                    require_once('Graphics/set_new_password_form.php');
                                    
                                }
                            }
                            elseif($_SESSION['change_password_link_open_status']==false){
                                require_once('Graphics/Notifications/set_new_password_form/change_password_link_open_failed.php');
                                session_destroy();
                            }
                            unset($_SESSION['change_password_link_open_status']);
                            
                        }

                        if(isset($_GET['password_change_status'])){
                            if($_GET['password_change_status']=="yes"){
                                echo "<br><span class=\"successmessage\">Salasanan vaihto onnistui <a href=\"./index.php?page=frontpage\">Refresh page</a></span>";
                                session_destroy();
                            }
                            elseif($_GET['password_change_status']=="no"){
                                if(!isset($_GET['database_error'])){
                                    if(isset($_GET['real_email'])){
                                        require_once('Graphics/Notifications/set_new_password_form/set_new_password_failed_email.php');
                                        session_destroy();
                                    }
                                }
                                else{
                                    require_once('Graphics/Notifications/set_new_password_form/set_new_password_failed_database_error.php');
                                    session_destroy();
                                }
                            }
                        }
                            
                        /*echo "<h1>set_new_password_form, GET-muuttujat:</h1>";
                        foreach($_GET as $avain => $arvo){
                            echo "<br><h2>$avain : $arvo</h2>";
                        }
                        echo "<h1>set_new_password_form, SESSION-muuttujat:</h1>";
                        foreach($_SESSION as $avain => $arvo){
                            echo "<br><h2>$avain : $arvo</h2>";
                        }*/ 
                        unset($_SESSION['change_password_link_open_status']);
                    }
                    else{
                        require_once('Graphics/Notifications/set_new_password_form/cannot_open_directly.php');
                    }
                }
                else{
                    require_once('Graphics/Notifications/set_new_password_form/logged_in.php');
                }     
                break;        
            case 'registration_form':
                require_once('Graphics/Notifications/Notifications_registration_form.php');
                require_once('Graphics/registration_form.php');
                break;
            default:
                require_once('Graphics/frontpage.php');
        }
    }
    require_once('Graphics/frontpage.php');
?>

</body>


<?php
    require_once('Graphics/footer.php');
?>
</html>