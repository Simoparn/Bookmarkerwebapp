<?php

require_once('Eventhandlers/connect_database.php');

try{
//Jos ikkuna avataan uudestaan, kokeillaan automaattista kirjautumista Remember me- ja autentikaatiotoken-evästeillä
    if(isset($_COOKIE['rememberme']) && isset($_COOKIE['authentication_token'])){
        $selektorijavalidaattori=explode('.',$_COOKIE['authentication_token']);
        $selektori=$selektorijavalidaattori[0];
        $tokeninkäyttäjäkysely=$connection->prepare("SELECT username FROM userprofile WHERE username= (SELECT username FROM usertoken WHERE selektori=?)");
        $tokeninkäyttäjäkysely->bind_param("s",$selektori);
        if ($tokeninkäyttäjäkysely->execute()){
            $tokeninkäyttäjäkysely->bind_result($käyttäjänimi);
            while($tokeninkäyttäjäkysely->fetch()){
                if($käyttäjänimi){
                    session_start();
                    $_SESSION['username']=$käyttäjänimi;
                    //Uudelleenohjataan frontpagelle jos oikeat tunnukset löytyivät           
                    header('Location: ./index.php?page=frontpage&automaatic_login_status=yes');
                    exit();
                }
            }
            //Jos tunnuksia ei löytynyt, uudelleenohjataan frontpagelle
            header('Location: ./index.php?page=frontpage&automaatic_login_status=no');
        }
    }
    else{
        //Jos käyttäjänimeä ja salasanaa ei ole asetettu, ei periaatteessa tarvita, required-attribuutti kirjautumislomakkeella suojelee tältä tapaukselta
        header('Location: ./index.php?page=login_form&automatic_login_status=no');
    }
                
}catch(Exception $e){
    //database_error
    header('Location: ./index.php?page=login_form&login_status=unknown_error');
}


?>