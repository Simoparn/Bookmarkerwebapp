<?php
  


//Needed for dotenv, PHPmailer and Sendgrid libraries:
  require '../vendor/autoload.php';
  




  $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
  $DOTENVDATA=$dotenv->load();
  
  
  


  if(isset($_POST["name"])){
  $sender_name=$_POST["name"];
  }
  if(isset($_POST["email"])){
  $sender_email=strtolower(trim($_POST["email"]));
  }
  if(isset($_POST["feedbacktopic"])){
  $feedback_topic=$_POST["feedbacktopic"];
  }
  if(isset($_POST["feddbackmessage"])){
  $feedback_message=$_POST["feedbackmessage"];
  }

	$email_found=false;


  
    
    
    //not tested
    if($DOTENVDATA['MAILSERVICE']=="mailtrap"){
    require_once('../vendor/phpmailer/phpmailer/src/PHPMailer.php');
    require_once('../vendor/phpmailer/phpmailer/src/PHPMailer.php');
    require_once('../vendor/phpmailer/phpmailer/src/SMTP.php');

    

      $mail = new PHPMailer\PHPMailer\PHPMailer();

      $mail->IsSMTP();
      $mail->Mailer = "smtp";
      $mail->charSet="UTF-8";

      $mail->SMTPDebug  = 0;  
      $mail->Host = $DOTENVDATA['MAILTRAPHOSTDOMAIN'];
      $mail->SMTPAuth = true;
      $mail->Port = 2525;
      $mail->Username = $DOTENVDATA['MAILTRAPHOSTUSERNAME'];
      $mail->Password = $DOTENVDATA['MAILTRAPHOSTPASSWORD'];

			
      
      $mail->IsHTML(true);
      $mail->AddAddress($DOTENVDATA['MAILTRAPHOSTDOMAIN'], "Bookmarker web application");
      $mail->SetFrom($sender_email, utf8_decode($sender_name));
      $mail->AddReplyTo($sender_email, utf8_decode($sender_name));
      $mail->AddCC($sender_email, utf8_decode($sender_name));
      $mail->Subject = utf8_decode($feedback_topic);
      $content = utf8_decode($feedback_message);

      $mail->MsgHTML($content); 
      if(!$mail->Send()) {
        
        //echo "Error in sending email through Mailtrap.<br>{$mail->ErrorInfo}<br>";
        //var_dump($mail);
        //exit();
        header("Location: ../index.php?page=contact&send_feedback_status=no&mailservice=".$DOTENVDATA['MAILSERVICE']);
      } else {
        //echo "Sending feedback email through Mailtrap succeeded.<br>";
        //var_dump($mail);
        //exit();
        header("Location: ../index.php?page=contact&send_feedback_status=yes&mailservice=".$DOTENVDATA['MAILSERVICE']);
        
      }

    }
    //not tested, if setFrom sender identity doesn't exist in SendGrid, email is not sent.
    elseif($DOTENVDATA['MAILSERVICE']=="sendgrid"){
			
      require_once('../vendor/sendgrid/sendgrid/sendgrid-php.php');



      $email = new \SendGrid\Mail\Mail();
      $email->setFrom($sender_email, urldecode($sender_name));
      $email->setSubject(utf8_decode($feedback_topic));
      $email->addTo($DOTENVDATA['SENDGRIDRECEIVERIDENTITY'], "Simo P");
      $email->addContent("text/plain", utf8_decode($feedback_message));

      $sendgrid = new \SendGrid($DOTENVDATA['SENDGRIDHOSTPASSWORD']);

      try {
          $response = $sendgrid->send($email);
          print $response->statusCode() . "\n";
          print_r($response->headers());
          print $response->body() . "\n";
          if($response->statusCode()==202){
          //exit();
          header("Location: ../index.php?page=contact&send_feedback_status=yes&mailservice=".$DOTENVDATA['MAILSERVICE']);
          }
          else{
            header("Location: ../index.php?page=contact&send_feedback_status=no&mailservice=".$DOTENVDATA['MAILSERVICE']."&error=sendgrid_sender_identity_missing");
          }
      } catch (Exception $e) {
          header("Location: ../index.php?page=contact&send_feedback_status=no&mailservice=".$DOTENVDATA['MAILSERVICE']);
          //echo 'Error when sending email with SendGrid: '. $e->getMessage() ."\n";
          //exit();
      }
    }

  
    else{
      header("Location: ../index.php?page=forgotten_password_form&password_sent_status=no&mailservice=".$DOTENVDATA['MAILSERVICE']."&error=mailservice_not_found");
    }

			
		

	
  
?>