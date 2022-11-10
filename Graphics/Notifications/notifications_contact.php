<?php




if(isset($_GET['logout_status'])){
    if($_GET['logout_status'] =='yes'){
        echo "<br><span class=\"successmessage\">Logout succeeded <a href=\"./index.php?page=contact\">Refresh page</a></span>";
    }

    elseif($_GET['logout_status'] =='no'){
        echo "<br><span class=\"errormessage\">Logout failed <a href=\"./index.php?page=contact\">Refresh page</a></span>";
    }
}
if(isset($_GET['mailservice'])){
    if($_GET['mailservice']=='mailtrap'){
        if(isset($_GET['send_feedback_status'])){
            if($_GET['send_feedback_status'] =='yes'){
                echo "<br><span class=\"successmessage\">Sending feedback succeeded <a href=\"./index.php?page=contact\">Refresh page</a></span>";
            }
            elseif($_GET['send_feedback_status'] =='no'){
                if($_GET['send_feedback_status']=='mailtrap'){
                    echo "<br><span class=\"errormessage\">Sending feedback failed <a href=\"./index.php?page=contact\">Refresh page</a></span>";
                }
            }
        }
    } 
    elseif($_GET['mailservice']=='sendgrid'){
            if(isset($_GET['send_feedback_status'])){
                if($_GET['send_feedback_status'] =='yes'){
                    echo "<br><span class=\"successmessage\">Sending feedback succeeded <a href=\"./index.php?page=contact\">Refresh page</a></span>";
                }         

            else{
                if(!isset($_GET['sendgrid_sender_identity_missing'])){
                    echo "<br><span class=\"errormessage\">Sending feedback failed, the email service may be temporarily unavailable <a href=\"./index.php?page=contact\">Refresh page</a></span>";
                }
                else{
                    echo "<br><span class=\"errormessage\">Sending feedback failed, no acceptable sender identity configured in the email service <a href=\"./index.php?page=contact\">Refresh page</a></span>";
                }
            }
        }
    }
    else{
        echo "<br><span class=\"errormessage\">Palautteen lähetys epäonnistui, palvelimella määritettyä sähköpostipalvelua ei ole olemassa! <a href=\"./index.php?page=contact\">Refresh page</a></span>";
    }
}




?>