<?php

if(isset($_GET['logout_status'])){
    if($_GET['logout_status']=='yes'){
        echo "<br><span class=\"successmessage\">Uloskirjautuminen onnistui <a href=\"./index.php?page=registration_form\">Refresh page</a></span>";
    }

    elseif($_GET['logout_status']=='no'){
        echo "<br><span class=\"errormessage\">Uloskirjautuminen epäonnistui <a href=\"./index.php?page=registration_form\">Refresh page</a></span>";
    }
}


?>