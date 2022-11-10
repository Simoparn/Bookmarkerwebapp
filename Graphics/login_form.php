
<div id="pagecontent">
<form method="post" action=".\Eventhandlers\User management\handle_login.php">
        <input type="text" name="käyttäjänimi" required/>
        <label><b>Käyttäjänimi</b></label>
        <input type="password" name="salasana" required/>
        <label><b>Salasana</b></label>
        <INPUT type="checkbox" name="rememberme" value="yes"/>
        <label><b>Remember me</b></label>
        <input type="submit" name="kirjaudusisään" id="kirjaudusisään" value="Kirjaudu sisään" 
        onClick="buttonpressedtext=window.document.getElementById('buttonpressedtext'); 
        buttonpressedtext.style.fontSize='larger'; buttonpressedtext.innerHTML='Odottakaa, sisäänkirjautumista käsitellään, tai korjatkaa virheet';;">
        <span id="buttonpressedtext"></span>

</form>


</div>