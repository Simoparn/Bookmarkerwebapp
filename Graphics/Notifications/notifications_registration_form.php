<?php

if(isset($_GET['logout_status'])){
    if($_GET['logout_status']=='yes'){
        echo "<br><span class=\"successmessage\">Logout succeeded <a href=\"./index.php?page=registration_form\">Refresh page</a></span>";
    }

    elseif($_GET['logout_status']=='no'){
        echo "<br><span class=\"errormessage\">Logout failed <a href=\"./index.php?page=registration_form\">Refresh page</a></span>";
    }
}

if(isset($_GET["countries_not_retrieved_error"])){
    echo "<br><span class=\"errormessage\">Database error, countries could not be retrieved, <a href=\"index.php?page=registration_form.php\">Refresh page</a></span>";
  }
  if(isset($_GET['registration_status'])){
    if($_GET['registration_status']=="yes"){
      echo "<br><span class=\"successmessage\">User creation succeeded <a href=\"index.php?page=registration_form\">Refresh page</a></span>";
    }
    elseif($_GET['registration_status']=="user_already_exists"){
      echo "<br><span class=\"errormessage\">User profile creation failed, user already exists <a href=\"index.php?page=registration_form\">Refresh page</a></span>";
    }
    elseif($_GET['registration_status']=="passwords_dont_match"){
      echo "<br><span class=\"errormessage\">User profile creation failed, passwords do not match <a href=\"index.php?page=registration_form\">Refresh page</a></span>";
    }
    else{
      echo "<br><span class=\"errormessage\">Unknown error in user profile creation <a href=\"index.php?page=registration_form\">Refresh page</a></span>";
    }
}



?>