<div id="pagecontent">
    <h3><b>Input user email, a link will be sent to the email for setting a new password</b></h3>
    <form method="post" action=".\Eventhandlers\send_forgotten_password_email.php">
        <input type="text" name="email" id="email" placeholder="Email address">
        <input type="submit" name="form" id="form" value="Send the password change link" onClick="buttonpressedtext=window.document.getElementById('buttonpressedtext');
        buttonpressedtext.style.fontSize='larger'; buttonpressedtext.innerHTML='Please wait, the given email is being processed';">
        <span id="buttonpressedtext"></span>
    </form>
</div>