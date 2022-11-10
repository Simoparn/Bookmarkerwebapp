<?php

//if(isset($_SESSION['käyttäjänimi'])){
//  session_start();
//}

//Suositellaan basenamen sijasta windows/linux-yhteensopivuuden takaamiseksi
function mb_basename($path) {
  if (preg_match('@^.*[\\\\/]([^\\\\/]+)$@s', $path, $matches)) {
      return $matches[1];
  } else if (preg_match('@^([^\\\\/]+)$@s', $path, $matches)) {
      return $matches[1];
  }
  return '';
}
?>

        <span id="pagelogo"/>
        <span id="companytitle"><a href="./index.php?page=frontpage">Puutarhaliike Neilikka</a></span>

<?php
  if(str_contains($_SERVER['QUERY_STRING'], '&')){
    //explodea/implodea tarvitaan koska kyselymuuttujien hasheissa voi olla kenoviiva, jolloin mb_basename luulee jonkin hashin osaa koko query_stringiksi
    $vainsivumuuttuja=strstr(mb_basename(implode(explode("/",$_SERVER['QUERY_STRING']))),'&',true);
    //echo $vainsivumuuttuja;
    $nykyinenpage=rawurldecode(substr(strstr($vainsivumuuttuja,'=',false),1)); 
    
  }
  else{
    $nykyinenpage=rawurldecode(substr(strstr($_SERVER['QUERY_STRING'],'=',false),1));
  }
  
?>
      <div id="navbar">
        <div id="main-nav">
            <ul>
              <li><a href="./index.php?page=frontpage" <?php if($nykyinensivu == 'frontpage'){echo 'id="here"';}?>>frontpage</a></li>
              <li><a href="./index.php?page=tuotteet" <?php if($nykyinensivu == 'tuotteet'){echo 'id="here"';}?>>Tuotteet</a>
                <ul class="submenu">
                  <li><a href="./index.php?page=categorypage_1" <?php if($nykyinensivu == 'categorypage_1'){echo 'id="here"';}?>>categorypage_1</a></li>
                  <li><a href="./index.php?page=categorypage_2" <?php if($nykyinensivu == 'categorypage_2'){echo 'id="here"';}?>>categorypage_2</a></li>
                  <li><a href="./index.php?page=categorypage_3" <?php if($nykyinensivu == 'categorypage_3'){echo 'id="here"';}?>>categorypage_3</a></li>
                  <li><a href="./index.php?page=categorypage_4" <?php if($nykyinensivu == 'categorypage_4'){echo 'id="here"';}?>>categorypage_4</a></li>
                </ul>	
              </li>
              <li><a href="./index.php?page=myymälät" <?php if($nykyinensivu == 'myymälät'){echo 'id="here"';}?>>Myymälät</a></li>
              <li><a href="./index.php?page=about_us" <?php if($nykyinensivu == 'about_us'){echo 'id="here"';}?>>about_us</a></li>
              <li><a href="./index.php?page=contact" <?php if($nykyinensivu == 'contact'){echo 'id="here"';}?>>contact</a></li>
            </ul>
        </div>
          <div id="loginandsignupbox">
          <ul>

<?php          
          if(isset($_SESSION['käyttäjänimi'])){
            echo "<li>Käyttäjä: ".$_SESSION['käyttäjänimi']."</li>";
          }
          else{
            echo "<li style=\"color:grey; list-style-type:none\">Ei kirjauduttu sisään</li>";
          }
?>
<?php
          if(!isset($_SESSION['käyttäjänimi'])){
            if($nykyinenpage=="login_form"){
              echo "<li><a href=".("./index.php?page=login_form")." id=\"here\">Kirjaudu sisään</a></li>";
            }
            else{
              echo "<li><a href=".("./index.php?page=login_form").">Kirjaudu sisään</a></li>";
            }
          }
          else{
            echo "<li><a href=\"Eventhandlers/User management/handle_logout.php?kirjauduulos=kyllä\">Kirjaudu ulos</a></li>";
          }
          if($nykyinenpage=="registration_form"){
             echo "<li><a href=\"index.php?page=registration_form\" id=\"here\">Rekisteröidy</a></li>";
          }
          else{
           echo "<li><a href=\"index.php?page=registration_form\">Rekisteröidy</a></li>";
          }
?>
<?php
if(!isset($_SESSION['käyttäjänimi'])){
  if($nykyinenpage=="forgotten_password_form"){
    echo "<li><a href=\"./index.php?page=forgotten_password_form\" id=\"here\"><b>Oletko unohtanut salasanasi?</b></a></li>";
  }
  else{
    echo "<li><a href=\"./index.php?page=forgotten_password_form\"><b>Oletko unohtanut salasanasi?</b></a></li>";
  }
}
?>
          </ul>
          </div>
      </div>
        
  

    
