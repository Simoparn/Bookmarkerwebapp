<?php

    
require dirname(dirname((__DIR__))).'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
$DOTENVDATA=$dotenv->load();
    
//foreach($DOTENVDATA as $key => $value){
//    echo $key.' '.$value;
//}
//exit();

try{


    require_once('../connect_database.php');
    //Jos kirjaudutaan normaalisti
    if(isset($_POST["käyttäjänimi"]) && isset($_POST["salasana"])){
        //$sahkopostiregex="/^.{16,}";
        $annettukäyttäjänimi=$_POST["käyttäjänimi"];
        $annettusalasana=$_POST["salasana"];
        
        
        
        $tietokantakysely->prepare("SELECT kayttajanimi, salasanahash FROM kayttajatili WHERE kayttajanimi=?");
        $tietokantakysely->bind_param("s",$annettukäyttäjänimi);
        if ($tietokantakysely->execute()){

            $tietokantakysely->bind_result($käyttäjänimi, $salasanahash);
            while($tietokantakysely->fetch()){
                //echo "Sisäänkirjautumisen tietokantakyselyn tulokset:".$käyttäjänimi." ". $salasanahash;
                //exit();
                $oikeasalasana=password_verify($annettusalasana, $salasanahash);
                            
                
                if($annettukäyttäjänimi==$käyttäjänimi && $oikeasalasana==true){
                    //TODO: tulee varoitusviesti että sessio on jo käynnissä, ei tarvita?
                    //session_start();
                    $_SESSION['käyttäjänimi']=$käyttäjänimi;
                    
                    if(isset($_POST["rememberme"])){
                        require_once('create_authentication_token.php');
                    }
                

                    //Uudelleenohjataan frontpagelle jos oikeat tunnukset löytyivät           
                    header('Location: ../../index.php?page=frontpage&login_status=kyllä');
                    exit();
                }
            }
                
        
                //Jos oikeita tunnuksia ei löytynyt, uudelleenohjataan takaisin
                header('Location: ../../index.php?page=login_form&login_status=ei');
                
        
        }
    }
               
}catch(Exception $e){
    //database_error
    //echo $e;
    //exit();
    header('Location: ../../index.php?page=login_form&login_status=tuntematonvirhe');
}



?>