<?php





session_start();
if(isset($_GET["kirjauduulos"]) == "yes"){
    $_SESSION["kayttajanimi"]=$_POST["kayttajanimi"];
    $_SESSION["salasana"]=$_POST["salasana"];
    
    // poista  Remember me- ja autentikaatiotoken- evästeet
    if (isset($_COOKIE['rememberme'])) {
        unset($_COOKIE['rememberme']);
        setcookie('rememberme', null, -1000);
    }

    // poista  Remember me- ja autentikaatiotoken- evästeet
    if (isset($_COOKIE['autentikaatiotoken'])) {
        unset($_COOKIE['autentikaatiotoken']);
        setcookie('autentikaatiotoken', null, -1000);
    }
    

    
    
    
    
    session_destroy();
    unset($_SESSION["kayttajanimi"]);
    //Ohjataan takaisin frontpagelle
    //TODO: samalle sivulle?
    header('Location: ../../index.php?page=frontpage&logout_status=kyllä');
    
}
else{
    //Ohjataan takaisin frontpagelle
    //TODO: samalle sivulle?
    header('Location: ../../index.php?page=frontpage&logout_status=ei');
}

?>