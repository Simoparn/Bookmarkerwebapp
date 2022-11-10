<?php

if(isset($_GET['logout_status'])){
    if($_GET['logout_status'] =='yes'){
        echo "<br><span class=\"successmessage\">Logout succeeded <a href=\"./index.php?page=forgotten_password_form\">Refresh page</a></span>";
    }

    elseif($_GET['logout_status'] =='no'){
        echo "<br><span class=\"errormessage\">Logout failed <a href=\"./index.php?page=forgotten_password_form\">Refresh page</a></span>";
    }
}
if(isset($_GET['send_password_change_link_status'])){ 
    if($_GET['send_password_change_link_status'] == 'yes'){
            echo "<br><span class=\"successmessage\">Sending change link for forgotten password succeeded, please check your email <a href=\"./index.php?page=forgotten_password_form\">Refresh page</a></span>";
    }
    if($_GET['send_password_change_link_status'] == 'no'){
        if(isset($_GET['error'])){
            if($_GET['error'] == "user_email_not_found"){
                echo "<br><span class=\"errormessage\">Sending change link for forgotten password failed, user email not found <a href=\"./index.php?page=forgotten_password_form\">Refresh page</a></span>";
            }  
            elseif($_GET['error'] == 'database_error'){
                echo "<br><span class=\"errormessage\">Unohtuneen salasanan lähetys sähköpostiosoitteeseen epäonnistui, database_error <a href=\"./index.php?page=forgotten_password_form\">Refresh page</a></span>";
            } 
            elseif($_GET['error'] == 'email_service_not_found'){
                echo "<br><span class=\"errormessage\">Palautteen lähetys epäonnistui, palvelimella määritettyä sähköpostipalvelua ei ole olemassa! <a href=\"./index.php?page=contact\">Refresh page</a></span>";
            }
        }
    }

}




?>