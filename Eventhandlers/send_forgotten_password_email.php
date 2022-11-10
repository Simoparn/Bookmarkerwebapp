<?php
  


   //Tarvitaan dotenv-, PHPmailer- ja Sendgrid-kirjastoille:
  require '../vendor/autoload.php';
  



  $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
  $DOTENVDATA=$dotenv->load();
  require_once('connect_database.php');
  
  
  

  
  if(isset($_POST["nimi"])){
  $lähettäjännimi=$_POST["nimi"];
  }
  if(isset($_POST["sähköposti"])){
  $lähettäjänsähköposti=strtolower(trim($_POST["sähköposti"]));
  }
  if(isset($_POST["palauteaihe"])){
  $palauteaihe=$_POST["palauteaihe"];
  }
  if(isset($_POST["palauteviesti"])){
  $palauteviesti=$_POST["palauteviesti"];
  }

	$sähköpostilöydetty=false;

 
    
    
    //testattu lähetys
    if($DOTENVDATA['MAILSERVICE']=="mailtrap"){

      require_once('../vendor/phpmailer/phpmailer/src/PHPMailer.php');
      require_once('../vendor/phpmailer/phpmailer/src/Exception.php');
      require_once('../vendor/phpmailer/phpmailer/src/SMTP.php');

      
      //$palauteviesti=$_POST["palauteviesti"];



      $mail = new PHPMailer\PHPMailer\PHPMailer();

      $mail->IsSMTP();
      $mail->Mailer = "smtp";
      $mail->CharSet='UTF-8';
      $mail->Encoding="base64";

      $mail->SMTPDebug  = 0;  
      $mail->Host = $DOTENVDATA['MAILTRAPHOSTDOMAIN'];
      $mail->SMTPAuth = true;
      $mail->Port = 2525;
      $mail->Username = $DOTENVDATA['MAILTRAPHOSTUSERNAME'];
      $mail->Password = $DOTENVDATA['MAILTRAPHOSTPASSWORD'];

      try{
        $haesähköpostikysely=$connection->prepare("SELECT sahkoposti, kayttajanimi, etunimi, sukunimi FROM kayttajatili WHERE LCASE(TRIM(sahkoposti))=?");
        $haesähköpostikysely->bind_param("s",$lähettäjänsähköposti);
        if($haesähköpostikysely->execute()){
        	$haesähköpostikysely->bind_result($sähköposti, $käyttäjänimi, $etunimi, $sukunimi);
        	while($haesähköpostikysely->fetch()){
          	//echo $sähköposti.''.$käyttäjänimi;
          	if($lähettäjänsähköposti==$sähköposti){
							$sähköpostilöydetty=true;
							$haettuetunimi=$etunimi;
							$haettusukunimi=$sukunimi;
            	//koodataan tietoturvallista uusintalinkkiä varten
            	$koodattulähettäjänsähköposti=password_hash($sähköposti, PASSWORD_DEFAULT);
            	$koodattucustomerusername=password_hash($käyttäjänimi, PASSWORD_DEFAULT);
            	break;
          	}
        	}
          
          
      	}
      }catch(Exception $e){
        //echo $e;
        header("Location: ../index.php?page=forgotten_password_form&salasananlähetysonnistui=ei&sähköpostipalvelu=".$DOTENVDATA['MAILSERVICE']."&virhe=database_error");
        exit();
      }

			if($sähköpostilöydetty==true){
        //echo "Sähköposti löydetty mailtrapilla!";
        //exit();
				$mail->IsHTML(true);
				$mail->AddAddress($lähettäjänsähköposti, urldecode($haettuetunimi).' '.urldecode($haettusukunimi));
				$mail->SetFrom($DOTENVDATA['MAILTRAPHOSTDOMAIN'], 'Puutarhaliike Neilikka ');
				$mail->AddReplyTo($DOTENVDATA['MAILTRAPHOSTDOMAIN'], "Puutarhaliike Neilikka");
				$mail->AddCC($DOTENVDATA['MAILTRAPHOSTDOMAIN'], "Puutarhaliike Neilikka");
				$mail->Subject = "Käyttäjätilin unohtuneen salasanan uusintalinkki";
				$content = "Automaattinen viesti, älkää vastatko tähän viestiin. \nTälle sähköpostille on pyydetty salasanan uusintaa. Neilikan käyttäjän uusintalomakelinkki: http://localhost/Omnia-repositoryt/Puutarhaliike-Neilikka/index.php?page=set_new_password_form&sähköposti=$koodattulähettäjänsähköposti&käyttäjänimi=$koodattucustomerusername";
				

				$mail->MsgHTML($content); 
				if(!$mail->Send()) {
					//echo "Virhe sähköpostin lähetyksessä Mailtrap-palvelun kautta.<br>{$mail->ErrorInfo}<br>";
					//var_dump($mail);
          header("Location: ../index.php?page=forgotten_password_form&salasananlähetysonnistui=ei&sähköpostipalvelu=".$DOTENVDATA['MAILSERVICE']."&virhe=sähköpostivirhe");
				} else {
					header("Location: ../index.php?page=forgotten_password_form&salasananlähetysonnistui=kyllä&sähköpostipalvelu=".$DOTENVDATA['MAILSERVICE']);
					//echo "Sähköposti lähetetty onnistuneesti Mailtrap-palvelun kautta.";
				}
			}
			else{
				header("Location: ../index.php?page=forgotten_password_form&salasananlähetysonnistui=ei&sähköpostipalvelu=".$DOTENVDATA['MAILSERVICE']."&virhe=sähköpostiaeilöytynyt");
			}

    }

  //testattu lähetys
    elseif($DOTENVDATA['MAILSERVICE']=="sendgrid"){

      require_once('../vendor/sendgrid/sendgrid/sendgrid-php.php');
 
      try{
        $haesähköpostikysely=$connection->prepare("SELECT sahkoposti, kayttajanimi, etunimi, sukunimi FROM kayttajatili WHERE LCASE(TRIM(sahkoposti))=?");
        $haesähköpostikysely->bind_param("s",$lähettäjänsähköposti);
				if($haesähköpostikysely->execute()){     
					$haesähköpostikysely->bind_result($sähköposti, $käyttäjänimi, $etunimi, $sukunimi);
          while($haesähköpostikysely->fetch()){
            if($lähettäjänsähköposti==$sähköposti){
              
              $haettuetunimi=$etunimi;
              $haettusukunimi=$sukunimi;
							$sähköpostilöydetty=true;
              //koodataan tietoturvallista uusintalinkkiä varten
              $koodattulähettäjänsähköposti=password_hash($lähettäjänsähköposti, PASSWORD_DEFAULT);
              $koodattucustomerusername=password_hash($lähettäjänsähköposti, PASSWORD_DEFAULT);
              break;
            }
          }
          
        }
      }catch(Exception $e){
					echo "database_error:".$e;
          //exit();
          header("Location: ../index.php?page=forgotten_password_form&salasananlähetysonnistui=ei&sähköpostipalvelu=".$DOTENVDATA['MAILSERVICE']."&virhe=database_error");
          
        }

        if($sähköpostilöydetty==true){
          try{
            
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($DOTENVDATA['SENDGRIDSENDERIDENTITY'], 'Puutarhaliike Neilikka');
            $email->setSubject('Puutarhaliike Neilikka, käyttäjätilin unohtuneen salasanan uusintalinkki');
            $email->addTo($lähettäjänsähköposti, urldecode($haettuetunimi).' '.urldecode($haettusukunimi) );
            $email->addContent("text/plain", "Automaattinen viesti, älkää vastatko tähän viestiin. \nTälle sähköpostille on pyydetty salasanan uusintaa. Neilikan käyttäjän uusintalomakelinkki: http://localhost/Omnia-repositoryt/Puutarhaliike-Neilikka/index.php?page=set_new_password_form&salasananlähetysonnistui=kylla&sähköposti=".$koodattulähettäjänsähköposti."&käyttäjänimi=".$koodattucustomerusername);
            
            $sendgrid = new \SendGrid($DOTENVDATA['SENDGRIDHOSTPASSWORD']);
  
          
            $response = $sendgrid->send($email);
            header("Location: ../index.php?page=forgotten_password_form&salasananlähetysonnistui=kyllä&sähköpostipalvelu=".$DOTENVDATA['MAILSERVICE']);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
            //exit();
            
          } catch (Exception $e) {
            //echo "Sähköpostivirhe sendGrid-kirjastolla: ".$e;
            //exit();
            header("Location: ../index.php?page=forgotten_password_form&salasananlähetysonnistui=ei&sähköpostipalvelu=".$DOTENVDATA['MAILSERVICE']."&virhe=sähköpostivirhe");
            
            //echo "Virhe lähetettäessä sähköpostia SendGrid-kirjastolla: ". $e->getMessage() ."\n";
          }
        }
        else{
          //echo "sähköpostia ei löytynyt";
          //exit();
          header("Location: ../index.php?page=forgotten_password_form&salasananlähetysonnistui=ei&sähköpostipalvelu=".$DOTENVDATA['MAILSERVICE']."&virhe=sähköpostiaeilöytynyt");
        }
				
    }

    else{
      header("Location: ../index.php?page=forgotten_password_form&salasananlähetysonnistui=ei&sähköpostipalvelu=".$DOTENVDATA['MAILSERVICE']."&virhe=sähköpostipalveluaeilöytynyt");
    }
			
		
  
	
  
?>