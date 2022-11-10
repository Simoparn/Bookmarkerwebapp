<?php

//if(isset($_SESSION['username'])){
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
        <span id="companytitle"><a href="./index.php?page=frontpage">Bookmarker app</a></span>

<?php
  if(str_contains($_SERVER['QUERY_STRING'], '&')){
    //explodea/implodea tarvitaan koska kyselymuuttujien hasheissa voi olla kenoviiva, jolloin mb_basename luulee jonkin hashin osaa koko query_stringiksi
    $onlypagevariable=strstr(mb_basename(implode(explode("/",$_SERVER['QUERY_STRING']))),'&',true);
    //echo $vainsivumuuttuja;
    $currentpage=rawurldecode(substr(strstr($onlypagevariable,'=',false),1)); 
    
  }
  else{
    $currentpage=rawurldecode(substr(strstr($_SERVER['QUERY_STRING'],'=',false),1));
  }
  
?>
      <div id="navbar">
        <div id="main-nav">
            <ul>
              <li><a href="./index.php?page=frontpage" <?php if($currentpage == 'frontpage'){echo 'id="here"';}?>>Frontpage</a></li>
              <li><a href="./index.php?page=categories" <?php if($currentpage == 'categories'){echo 'id="here"';}?>>Categories</a>
                <ul class="submenu">
                  <li><a href="./index.php?page=categorypage_1" <?php if($currentpage == 'categorypage_1'){echo 'id="here"';}?>>categorypage_1</a></li>
                  <li><a href="./index.php?page=categorypage_2" <?php if($currentpage == 'categorypage_2'){echo 'id="here"';}?>>categorypage_2</a></li>
                  <li><a href="./index.php?page=categorypage_3" <?php if($currentpage == 'categorypage_3'){echo 'id="here"';}?>>categorypage_3</a></li>
                  <li><a href="./index.php?page=categorypage_4" <?php if($currentpage == 'categorypage_4'){echo 'id="here"';}?>>categorypage_4</a></li>
                </ul>	
              </li>
              <li><a href="./index.php?page=about_us" <?php if($currentpage == 'about_us'){echo 'id="here"';}?>>About us</a></li>
              <li><a href="./index.php?page=contact" <?php if($currentpage == 'contact'){echo 'id="here"';}?>>Contact</a></li>
            </ul>
        </div>
          <div id="loginandsignupbox">
          <ul>

<?php          
          if(isset($_SESSION['username'])){
            echo "<li>Käyttäjä: ".$_SESSION['username']."</li>";
          }
          else{
            echo "<li style=\"color:grey; list-style-type:none\">Not logged in</li>";
          }
?>
<?php
          if(!isset($_SESSION['username'])){
            if($currentpage=="login_form"){
              echo "<li><a href=".("./index.php?page=login_form")." id=\"here\">Log in</a></li>";
            }
            else{
              echo "<li><a href=".("./index.php?page=login_form").">Log in</a></li>";
            }
          }
          else{
            echo "<li><a href=\"Eventhandlers/User management/handle_logout.php?kirjauduulos=kyllä\">Kirjaudu ulos</a></li>";
          }
          if($currentpage=="registration_form"){
             echo "<li><a href=\"index.php?page=registration_form\" id=\"here\">Sign up</a></li>";
          }
          else{
           echo "<li><a href=\"index.php?page=registration_form\">Sign up</a></li>";
          }
?>
<?php
if(!isset($_SESSION['username'])){
  if($currentpage=="forgotten_password_form"){
    echo "<li><a href=\"./index.php?page=forgotten_password_form\" id=\"here\"><b>Forgot your password?</b></a></li>";
  }
  else{
    echo "<li><a href=\"./index.php?page=forgotten_password_form\"><b>Forgot your password?</b></a></li>";
  }
}
?>
          </ul>
          </div>
      </div>
        
  

    
