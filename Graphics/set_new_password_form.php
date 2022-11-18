<!-- Not rendered if the username and email hashes included in the forgotten password change link do not match some database record
, misuser must know both the username and the email to forge a functional change link and even then the old password is needed-->
<div id="pagecontent">

<b>Setting a new password</b>
    <form method="post" action=".\Eventhandlers\User_management\set_new_password.php">
        <br><b>User email address</b><input type="text"  name="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"required>
        <br><b>New password (must include at least one number and at least 8 characters in total)</b><input type="password"  name="new_password" id="new_password" pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,}"required>
        <br><b>Confirm new password (must include at least one number and at least 8 characters in total)</b><input type="password"  name="confirm_new_password" id="confirm_new_password" pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,}"required>
        <br><b><input type="submit" name="new_password_has_been_set" value="Set a new password"/></b>
    </form>

</div>





