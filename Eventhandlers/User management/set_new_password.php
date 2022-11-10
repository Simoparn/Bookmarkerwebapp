<?php
require_once('../connect_database.php');
//TODO: Session already started- varoitus, ei tarvita täällä?
//session_start();
$annettusähköposti=$_POST["sähköposti"];
$uusisalasana=$_POST["uusisalasana"];
$annettusalasananvahvistus=$_POST["vahvistauusisalasana"];



$haekäyttäjiensähköpostitkysely=$connection->prepare("SELECT sahkoposti FROM kayttajatili");
$uusisalasanakysely=$connection->prepare("UPDATE kayttajatili SET salasanahash=? WHERE sahkoposti=?");


try{
    if($haekäyttäjiensähköpostitkysely->execute()){
        $haekäyttäjiensähköpostitkysely->store_result();
        $haekäyttäjiensähköpostitkysely->bind_result($sähköposti);
        while($haekäyttäjiensähköpostitkysely->fetch()){
            if(password_verify($annettusähköposti, $sähköpostihash)){
                if($uusisalasana == $annettusalasananvahvistus){
                    $salasanahash=password_hash($uusisalasana, PASSWORD_DEFAULT);
                    //päivitä jos sekä vaihtolomakkeella annettu sähköposti että uudet salasanat ovat oikein
                    $uusisalasanakysely->bind_param("ss",$salasanahash,$sähköposti);
                    if($uusisalasanakysely->execute()){
                        header('Location: ../../index.php?page=set_new_password_form&password_change_status=kyllä&oikeasähköposti=kyllä');
                        exit();
                    }
                }
                else{
                    //Jos uusi salasana ja uuden salasanan vahvistus eivät täsmää 

                    header('Location: ../../index.php?page=set_new_password_form&password_change_status=ei&oikeasähköposti=kyllä');
                    exit();
                    
                }
            }
        }
        $haekäyttäjiensähköpostitkysely->free_result();
        
            header('Location: ../../index.php?page=set_new_password_form&password_change_status=ei&oikeasähköposti=ei');
    }
        
    
}catch(Exception $e){
    echo "database_error: ".$e;  
    exit();             
    header('Location: ../../index.php?page=set_new_password_form&password_change_status=ei&database_error=kyllä');
    exit();
}
?>