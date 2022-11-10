<?php

require_once('Eventhandlers/connect_database.php');


/* check the remember_me in cookie
$token = filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING);

if ($token && token_is_valid($token)) {

    $user = find_user_by_token($token);

    if ($user) {
        return log_user_in($user);
    }
}*/

if(!isset($_SESSION["käyttäjänimi"]) && isset($_COOKIE["autentikaatiotoken"])){
    $autentikaatiotoken=filter_input(INPUT_COOKIE,"autentikaatiotoken",FILTER_SANITIZE_STRING);
    $selektorijavalidaattori=explode(".",$autentikaatiotoken);
    $selektori=$selektorijavalidaattori[0];
    $validaattori=$selektorijavalidaattori[1];
    
    $check_authentication_tokenkysely=$connection->prepare("SELECT kayttajanimi, selektori, validaattorihash, umpeutumisaika FROM kayttajantoken WHERE selektori = ? and umpeutumisaika > NOW()");
    $check_authentication_tokenkysely->bind_param("s",$selektori);
    try{
        if($check_authentication_tokenkysely->execute()){
            $check_authentication_tokenkysely->bind_result($käyttäjänimi,$selektori,$validaattorihash,$umpeutumisaika);
            while($check_authentication_tokenkysely->fetch()){
                echo $käyttäjänimi;
                if(password_verify($validaattori,$validaattorihash)==true){
                    session_start();
                    $_SESSION["käyttäjänimi"]=$käyttäjänimi;
                    require_once('Eventhandlers/User management/käsitteleautomaattinensisäänkirjautuminen.php');
                    //Uudelleenohjataan jos oikea token löytyi   
                    header('Location: ./index.php?page=frontpage&automaattinenlogin_status=kyllä');
                    exit();
                }
            }
            //Jos voimassaolevaa autentikaatiotokenia ei löytynyt tietokannasta vaikka tokeneväste on selainpuolella, 
            // tulee vähintään poistaa evästeet selainpuolelta, tietokantapoiston voi hoitaa seuraavilla evästeettömillä sivuavauksilla
            if(isset($_COOKIE['rememberme'])){
                unset($_COOKIE['rememberme']);
                setcookie('rememberme', null, -1);
            }
            if(isset($_COOKIE['autentikaatiotoken'])){
                unset($_COOKIE['autentikaatiotoken']);
                setcookie('autentikaatiotoken', null, -1);
            }

            

            header('Location: ../../index.php?page=frontpage&automaattinenlogin_status=ei');
        }
       
        

    }catch(Exception $e){
        //database_error
        header('Location: ../../index.php?page=frontpage&database_error=kyllä');
    }

}







?>