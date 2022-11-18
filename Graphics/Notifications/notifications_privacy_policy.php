<?php
if(isset($_GET['logout_status'])){
    if($_GET['logout_status'] =='yes'){
        echo "<br><span class=\"successmessage\">Logout succeeded <a href=\"./index.php?page=about_us\">Refresh page</a></span>";
    }

    elseif($_GET['logout_status'] =='no'){
        echo "<br><span class=\"errormessage\">Logout failed <a href=\"./index.php?page=about_us\">Refresh page</a></span>";
    }
}
?>