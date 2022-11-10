<div id="pagecontent">
    <h3><b>Syötä käyttäjän sähköpostiosoite, linkki lähetetään sähköpostiosoitteeseen unohtuneen salasanan uusimiseksi:</b></h3>
    <form method="post" action=".\Eventhandlers\send_forgotten_password_email.php">
        <input type="text" name="sähköposti" id="sähköposti" placeholder="sähköpostiosoite">
        <input type="submit" name="lomake" id="lomake" value="Lähetä uusintalinkki" onClick="buttonpressedtext=window.document.getElementById('buttonpressedtext');
        buttonpressedtext.style.fontSize='larger'; buttonpressedtext.innerHTML='Odottakaa, syötettyä sähköpostia käsitellään';">
        <span id="buttonpressedtext"></span>
    </form>
</div>