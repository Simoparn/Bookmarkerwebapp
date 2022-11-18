<?php
  


   //Needed for dotenv, PHPmailer and Sendgrid packages:
  require '../vendor/autoload.php';
  



  $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
  $DOTENVDATA=$dotenv->load();
  require_once('connect_database.php');
  
  
  

  
  if(isset($_POST["name"])){
  $sender_name=$_POST["name"];
  }
  if(isset($_POST["email"])){
  $sender_email=strtolower(trim($_POST["email"]));
  }
  if(isset($_POST["feedback_topic"])){
  $feedback_topic=$_POST["feedback_topic"];
  }
  if(isset($_POST["feedback_message"])){
  $feedback_message=$_POST["feedback_message"];
  }

	$email_found=false;

 
    
    
    //not tested
    if($DOTENVDATA['MAILSERVICE']=="mailtrap"){

      require_once('../vendor/phpmailer/phpmailer/src/PHPMailer.php');
      require_once('../vendor/phpmailer/phpmailer/src/Exception.php');
      require_once('../vendor/phpmailer/phpmailer/src/SMTP.php');

      
      //$feedback_message=$_POST["feedback_message"];



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
        $retrieve_email_query=$connection->prepare("SELECT email, username, first_name, surname FROM userprofile WHERE LCASE(TRIM(email))=?");
        $retrieve_email_query->bind_param("s",$sender_email);
        if($retrieve_email_query->execute()){
        	$retrieve_email_query->bind_result($email, $username, $first_name, $surname);
        	while($retrieve_email_query->fetch()){
          	//echo $email.''.$username;
          	if($sender_email==$email){
							$email_found=true;
							$retrieved_first_name=$first_name;
							$retrieved_surname=$surname;
            	//hashed for a secure change link
            	$hashed_sender_email=password_hash($email, PASSWORD_DEFAULT);
            	$hashed_customer_username=password_hash($username, PASSWORD_DEFAULT);
            	break;
          	}
        	}
          
          
      	}
      }catch(Exception $e){
        //echo $e;
        header("Location: ../index.php?page=forgotten_password_form&mailservice=".$DOTENVDATA['MAILSERVICE']."&error=database_error");
        exit();
      }

			if($email_found==true){
        //echo "email found with mailtrap!";
        //exit();
				$mail->IsHTML(true);
				$mail->AddAddress($sender_email, urldecode($retrieved_first_name).' '.urldecode($retrieved_surname));
				$mail->SetFrom($DOTENVDATA['MAILTRAPHOSTDOMAIN'], 'Bookmarker web application ');
				$mail->AddReplyTo($DOTENVDATA['MAILTRAPHOSTDOMAIN'], "Bookmarker web application");
				$mail->AddCC($DOTENVDATA['MAILTRAPHOSTDOMAIN'], "Bookmarker web application");
				$mail->Subject = "Change link for user profile's forgotten password";
				$content = "Automaatic message, do not reply to this message. \nUser password change link has been requested for this email. Please remove the link from your browser's history after successfully changing your password: http://localhost/Omnia-repositoryt/Bookmarkerwebapp/index.php?page=set_new_password_form&email=$hashed_sender_email&username=$hashed_customer_username";
				

				$mail->MsgHTML($content); 
				if(!$mail->Send()) {
					//echo "Error in sending email through Mailtrap<br>{$mail->ErrorInfo}<br>";
					//var_dump($mail);
          header("Location: ../index.php?page=forgotten_password_form&mailservice=".$DOTENVDATA['MAILSERVICE']."&error=email_error");
				} else {
					header("Location: ../index.php?page=forgotten_password_form&mailservice=".$DOTENVDATA['MAILSERVICE']);
					//echo "Email sent successfully through Mailtrap.";
				}
			}
			else{
				header("Location: ../index.php?page=forgotten_password_form&mailservice=".$DOTENVDATA['MAILSERVICE']."&error=email_not_found");
			}

    }

  //not tested
    elseif($DOTENVDATA['MAILSERVICE']=="sendgrid"){

      require_once('../vendor/sendgrid/sendgrid/sendgrid-php.php');
 
      try{
        $retrieve_email_query=$connection->prepare("SELECT email, username, first_name, surname FROM userprofile WHERE LCASE(TRIM(email))=?");
        $retrieve_email_query->bind_param("s",$sender_email);
				if($retrieve_email_query->execute()){     
					$retrieve_email_query->bind_result($email, $username, $first_name, $surname);
          while($retrieve_email_query->fetch()){
            if($sender_email==$email){
              
              $retrieved_first_name=$first_name;
              $retrieved_surname=$surname;
							$email_found=true;
              //hashed for a secure change link
              $hashed_sender_email=password_hash($sender_email, PASSWORD_DEFAULT);
              $hashed_customer_username=password_hash($username, PASSWORD_DEFAULT);
              break;
            }
          }
          
        }
      }catch(Exception $e){
					echo "Database error:".$e;
          //exit();
          header("Location: ../index.php?page=forgotten_password_form&mailservice=".$DOTENVDATA['MAILSERVICE']."&error=database_error");
          
        }

        if($email_found==true){
          try{
            
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($DOTENVDATA['SENDGRIDSENDERIDENTITY'], 'Bookmarker web application');
            $email->setSubject("Bookmarker web application, Change link for user profile's forgotten password");
            $email->addTo($sender_email, urldecode($retrieved_first_name).' '.urldecode($retrieved_surname) );
            $email->addContent("text/plain", "Automatic message, don't reply to this message. \nUser password change link has been requested for this email. Please remove the link from your browser's history after successfully changing your password: http://localhost/Omnia-repositoryt/Bookmarkerwebapp/index.php?page=set_new_password_form&email=".$hashed_sender_email."&username=".$hashed_customer_username);
            
            $sendgrid = new \SendGrid($DOTENVDATA['SENDGRIDHOSTPASSWORD']);
  
          
            $response = $sendgrid->send($email);
            if($response->statusCode()==202){
              header("Location: ../index.php?page=forgotten_password_form&send_password_change_link_status=yes&mailservice=".$DOTENVDATA['MAILSERVICE']);
            }
            else{
              header("Location: ../index.php?page=contact&send_password_change_link_status=no&mailservice=".$DOTENVDATA['MAILSERVICE']."&error=sendgrid_sender_identity_missing");
            }
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
            
            
          } catch (Exception $e) {
            //echo "Email error with SendGrid package: ".$e;
            //exit();
            header("Location: ../index.php?page=forgotten_password_form&send_password_change_link_status=no&mailservice=".$DOTENVDATA['MAILSERVICE']."&error=user_email_error");
            
            //echo "Error while sending email with SendGrid: ". $e->getMessage() ."\n";
          }
        }
        else{
          //echo "Email not found";
          //exit();
          header("Location: ../index.php?page=forgotten_password_form&send_password_change_link_status=no&mailservice=".$DOTENVDATA['MAILSERVICE']."&error=user_email_not_found");
        }
				
    }

    else{
      header("Location: ../index.php?page=forgotten_password_form&send_password_change_link_status=no&mailservice=".$DOTENVDATA['MAILSERVICE']."&error=mailservice_not_found");
    }
			
		
  
	
  
?>