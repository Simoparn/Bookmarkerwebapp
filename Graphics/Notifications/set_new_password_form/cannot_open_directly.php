<?php

echo "<br><span class=\"errormessage\">Password change link cannot be opened directly, please go to the \"Forgot your password\" page from where a working link can be sent to user email <a href=\"./index.php?page=frontpage\">Refresh page</a></span>";
                    
//echo "<h1>set_new_password_form, GET variables:</h1>";
//foreach($_GET as $key => $value){
//    echo "<br><h2>$key : $value</h2>";
//}
echo "<h1>set_new_password_form, SESSION variables:</h1>";
foreach($_SESSION as $key => $value){
    echo "<br><h2>$key : $value</h2>";
}
?>