
<div id="pagecontent">
<form method="post" action=".\Eventhandlers\User_management\handle_login.php">
        <input type="text" name="username_or_email" required/>
        <label><b>Username or email address</b></label>
        <input type="password" name="password" required/>
        <label><b>Password</b></label>
        <INPUT type="checkbox" name="rememberme" value="yes"/>
        <label><b>Remember me</b></label>
        <input type="submit" name="login" id="login" value="Log in" 
        onClick="buttonpressedtext=window.document.getElementById('buttonpressedtext'); 
        buttonpressedtext.style.fontSize='larger'; buttonpressedtext.innerHTML='Wait, the log in attempt is being processed, or fix the errors';">
        <span id="buttonpressedtext"></span>

</form>


</div>