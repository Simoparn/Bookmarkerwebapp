



<div id="pagecontent">
<?php
  
  
  if(isset($_GET["maitaeihaettuvirhe"])){
    echo "<br><span class=\"errormessage\">database_error, maita ei saatu haettua, <a href=\"index.php?page=registration_form.php\">Refresh page</a></span>";
  }
  if(isset($_GET['rekisteröintionnistui'])){
    if($_GET['rekisteröintionnistui']=="yes"){
      echo "<br><span class=\"successmessage\">Käyttäjän luonti onnistui <a href=\"index.php?page=registration_form\">Refresh page</a></span>";
    }
    elseif($_GET['rekisteröintionnistui']=="käyttäjäonjoolemassa"){
      echo "<br><span class=\"errormessage\">Käyttäjän luonti epäonnistui, käyttäjä on jo olemassa <a href=\"index.php?page=registration_form\">Refresh page</a></span>";
    }
    elseif($_GET['rekisteröintionnistui']=="salasanateivättäsmää"){
      echo "<br><span class=\"errormessage\">Käyttäjän luonti epäonnistui, salasanat eivät täsmää <a href=\"index.php?page=registration_form\">Refresh page</a></span>";
    }
    else{
      echo "<br><span class=\"errormessage\">Tuntematon virhe käyttäjän luonnissa <a href=\"index.php?page=registration_form\">Refresh page</a></span>";
    }
  }

?>
   <p>
      <form method="post" action="./Eventhandlers/User management/register_user.php">
      <br><b>Etunimi (vähintään 2 kirjainta)</b><input type="text" name="etunimi" id="etunimi" pattern="[a-öA-Ö]{2,}$"required><br>
      <br><b>Sukunimi (vähintään 2 kirjainta)</b><input type="text" name="sukunimi" id="sukunimi" pattern="[a-öA-Ö]{2,}$"required><br>
      <br><b>Puhelinnumero (pelkkiä numeroita, vähintään 8)</b><input type="text" name="puhelinnumero" id="puhelinnumero" pattern="[0-9]{8,}$"required><br>
      <br><b>Sähköposti (oltava muotoa nimi@osoite.com)</b><input type="text" name="sähköposti" id="sähköposti" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"required><br>
      <br><b>Osoite (vähintään 8 kirjainta, välilyönti ja 1-3 numeroa perässä)</b><input type="text" name="osoite" id="osoite" pattern="[a-öA-Ö]\w{8,}.[0-9]{1,3}$"required><br>
      <br><b>Postinumero (vähintään 5 numeroa)</b><input type="text" name="postinumero" id="postinumero" pattern="[0-9]{5,}$"required><br>
      <br><b>Postitoimipaikka (iso alkukirjain ja vähintään 4 kirjainta, välilyönnit sallittu)</b><input type="text" name="postitoimipaikka" id="postitoimipaikka" pattern="[A-Z]{1,1}.[a-zA-Z\ ]{3,}$"required><br>
      <select name="maa">
<?php
      $maakysely="SELECT nimi FROM maa";
      if($kyselyntulos = $connection->query($maakysely)){
        while(list($nimi)=$kyselyntulos->fetch_row()){
          echo "<br><option value=\"$nimi\">$nimi</option>";
          
      }

      }
      else{

        header('Location: ./index.php?page=registration_form.php?maitaeihaettuvirhe=true');
      }

?>
      </select>
      <br><b>Maakunta (iso alkukirjain ja vähintään neljä kirjainta, välilyönnit sallittu)</b><input type="text" name="maakunta" id="maakunta" pattern="[A-Z]{1,1}.[a-zA-Z\ ]{3,}$"required><br>
      <br><b>Osavaltio (saa olla tyhjä)</b><input type="text" name="osavaltio" id="osavaltio"><br>
      <br><b>Käyttäjänimi (sisällettävä vähintään 8 merkkiä)</b> <input type="text" name="käyttäjänimi" id="käyttäjänimi" pattern="[a-öA-Ö0-9-]{8,}$"required>  <br>   
      <br><b>Salasana (sisällettävä ainakin yksi numero ja yhteensä vähintään 8 merkkiä)</b> <input type="password" name="salasana" id="salasana" pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,}"required>
      <br><b>Vahvista salasana</b> <input type="password" name="vahvistasalasana" id="vahvistasalasana" pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,}"required>
      <input type="submit" name="register_user" id="register_user" value="Luo käyttäjä" onClick="buttonpressedtext=window.document.getElementById('buttonpressedtext'); buttonpressedtext.innerHTML='Odottakaa, käyttäjän rekisteröintiä käsitellään, tai korjatkaa virheet';">
      <span id="buttonpressedtext"></span>
      </form>
  </p>


</div>

