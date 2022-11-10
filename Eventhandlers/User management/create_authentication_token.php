<?php
if(isset($_SESSION["käyttäjänimi"])){
    $käyttäjänimi=$_SESSION["käyttäjänimi"];
    $selektori = bin2hex(random_bytes(16));
    $validaattori = bin2hex(random_bytes(32));
    $validaattorihash = password_hash($validaattori, PASSWORD_DEFAULT);
    //$nykypäivämäärä=date_format(date_create(), 'Y-m-d H:i:s');
    //$umpeutumisaika=date_add(date_interval_create_from_date_string("5 minutes"),$nykypäivämäärä);
    //echo "$nykypäivämäärä";
    //echo "<br>$umpeutumisaika";
    //exit();
    $tietokantakysely->prepare("INSERT INTO kayttajantoken (selektori,validaattorihash,kayttajanimi,umpeutumisaika) VALUES (?,?,?, NOW()+ INTERVAL 2 MINUTE)");
    $tietokantakysely->bind_param("sss",$selektori,$validaattorihash,$käyttäjänimi);
    try{
        if($tietokantakysely->execute($create_authentication_tokenkysely)){
                    //Uudelleenohjataan jos autentikaatiotokenin tallennus onnistui
                    setcookie("rememberme","rememberme",time()+120,"/");
                    setcookie("autentikaatiotoken",$selektori.".".$validaattori,time()+120,"/");
                    header('Location: ../../index.php?page=frontpage&create_authentication_token_status=kyllä');
                    exit();
        }
        else{
            header('Location: ../../index.php?page=frontpage&create_authentication_token_status=ei');
                    
        }
    } 
    catch(Exception $e){
        //database_error
        header('Location: ../../index.php?page=frontpage&database_error=kyllä');
    }

}
?>