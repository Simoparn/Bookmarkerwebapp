<?php

require_once('Eventhandlers/connect_database.php');

try{
//Jos ikkuna avataan uudestaan, kokeillaan automaattista kirjautumista Remember me- ja autentikaatiotoken-evästeillä
    if(isset($_COOKIE['rememberme']) && isset($_COOKIE['autentikaatiotoken'])){
        $selektorijavalidaattori=explode('.',$_COOKIE['autentikaatiotoken']);
        $selektori=$selektorijavalidaattori[0];
        $tokeninkäyttäjäkysely=$connection->prepare("SELECT kayttajanimi FROM kayttajatili WHERE kayttajanimi= (SELECT kayttajanimi FROM kayttajantoken WHERE selektori=?)");
        $tokeninkäyttäjäkysely->bind_param("s",$selektori);
        if ($tokeninkäyttäjäkysely->execute()){
            $tokeninkäyttäjäkysely->bind_result($käyttäjänimi);
            while($tokeninkäyttäjäkysely->fetch()){
                if($käyttäjänimi){
                    session_start();
                    $_SESSION['käyttäjänimi']=$käyttäjänimi;
                    //Uudelleenohjataan frontpagelle jos oikeat tunnukset löytyivät           
                    header('Location: ./index.php?page=frontpage&automaattinenlogin_status=kyllä');
                    exit();
                }
            }
            //Jos tunnuksia ei löytynyt, uudelleenohjataan frontpagelle
            header('Location: ./index.php?page=frontpage&automaattinenlogin_status=ei');
        }
    }
    else{
        //Jos käyttäjänimeä ja salasanaa ei ole asetettu, ei periaatteessa tarvita, required-attribuutti kirjautumislomakkeella suojelee tältä tapaukselta
        header('Location: ./index.php?page=login_form&login_status=ei');
    }
                
}catch(Exception $e){
    //database_error
    header('Location: ./index.php?page=login_form&login_status=tuntematonvirhe');
}


?>