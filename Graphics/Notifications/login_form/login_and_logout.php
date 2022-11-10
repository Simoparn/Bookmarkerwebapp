<?php

if(isset($_GET["login_status"])){            
    if($_GET["login_status"] =="no"){ 
        echo "<br><span class=\"errormessage\">Login failed, wrong username or password<a href=\"./index.php?page=login_form\">Refresh page</a></span>";
    }
    elseif($_GET['login_status'] =='tuntematonvirhe'){
        echo "<br><span class=\"errormessage\">Sisäänkirjautuminen epäonnistui, tuntematon virhe, yritä uudestaan <a href=\"./index.php?page=login_form\">Refresh page</a></span>";
    }
}
if(isset($_GET['logout_status'])){
    if($_GET['logout_status'] =='yes'){
        echo "<br><span class=\"successmessage\">Uloskirjautuminen onnistui <a href=\"./index.php?page=login_form\">Refresh page</a></span>";
    }

    elseif($_GET['logout_status'] =='no'){
        echo "<br><span class=\"errormessage\">Uloskirjautuminen epäonnistui <a href=\"./index.php?page=login_form\">Refresh page</a></span>";
    }         
}


?>