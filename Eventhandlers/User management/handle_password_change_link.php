<?php




if(isset($_GET['sähköposti'])){
    $linkinsähköpostihash=$_GET['sähköposti'];
}
if(isset($_GET['käyttäjänimi'])){
    $linkinkäyttäjänimihash=$_GET['käyttäjänimi'];
}



$tietokantakysely->prepare("SELECT sahkoposti, kayttajanimi FROM kayttajatili");
if($tietokantakysely->execute()){
    //TODO: Session already started, varoitusta ei tule ilman tätä virheellisellä linkillä ja oikean linkin avaus toimii, ei siis tarvita täällä?
    //session_start();
    $tietokantakysely->bind_result($sähköposti, $käyttäjänimi);
    while($tietokantakysely->fetch()){
        
        if(password_verify($sähköposti,$linkinsähköpostihash) && password_verify($käyttäjänimi, $linkinkäyttäjänimihash)){
            
            //viitataan nykyiseen kansioon, koska tapahtumankäsittelijää käytetään suoraan index.php:ssa eikä grafiikkakomponentin lomakkeen actionina
            //header('Location: ./index.php?page=set_new_password_form&change_password_link_open_status=kyllä&käyttäjänimihash='.$linkinkäyttäjänimihash);
            $_SESSION["change_password_link_open_status"]=true;
            $_SESSION["old_password_email"]=$sähköposti;
            header('Location: ./index.php?page=set_new_password_form');
            exit();
        }
       
        
    
    }
    
    $_SESSION["change_password_link_open_status"]=false;
    //header('Location: ./index.php?page=set_new_password_form');
    
}
else{
    echo "database_error salasanan vaihtolinkkiä avattaessa: ".$connection->error;
    session_start();
    $_SESSION["change_password_link_open_status"]=false;
    header('Location: ./index.php?page=set_new_password_form&database_error=kyllä');
}








?>