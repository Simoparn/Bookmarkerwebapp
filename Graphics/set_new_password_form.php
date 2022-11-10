<!-- Tätä ei renderöidä  ellei unohtuneen salasanan vaihtolinkissä olevat käyttäjänimi- ja sähköpostihashit täsmää jonkin tietokannan rivin tietojen kanssa
, väärinkäyttäjän pitäisi siis tietää sekä uhrin käyttäjänimi että sähköposti sopivan toimivan vaihtolinkin keksimiseksi ja silloinkin tarvittaisiin vielä vanha salasana-->
<div id="pagecontent">

<b>Uuden salasanan asetus</b>
    <form method="post" action=".\Eventhandlers\User management\set_new_password.php">
        <br><b>Käyttäjän sähköposti</b><input type="password"  name="sähköposti" id="sähköposti" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"required>
        <br><b>Uusi salasana(sisällettävä ainakin yksi numero ja yhteensä vähintään 8 merkkiä)</b><input type="password"  name="uusisalasana" id="uusisalasana" pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,}"required>
        <br><b>Vahvista uusi salasana(sisällettävä ainakin yksi numero ja yhteensä vähintään 8 merkkiä)</b><input type="password"  name="vahvistauusisalasana" id="vahvistauusisalasana" pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,}"required>
        <br><b><input type="submit" name="uusisalasanaasetettu" value="Aseta uusi salasana"/></b>
    </form>

</div>





