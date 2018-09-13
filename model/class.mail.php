<?

require '/../add-on/PHPMailer/PHPMailerAutoload.php';



class Mail
{
	
	public function invio(){
		
		$mail = new PHPMailer;
		$mail->isSMTP();   
		$mail->Host = 'smtp.raffaelelanzetta.com';
		$mail->SMTPAuth = true;  
		$mail->Username = 'mail@raffaelelanzetta.com'; 
		$mail->Password = 'webzero21280';
		$mail->SMTPSecure = 'tls'; 
		$mail->Port = 587;
		$mail->setLanguage('it', '/../add-on/PHPMailer/language/');
		
		
		$mail->setFrom('mail@raffaelelanzetta.com', 'Raffaele PHP');
		$mail->addAddress('r.lanzetta@gmail.com', 'Raffaele Lanzetta');     // Add a recipient
		$mail->addReplyTo('info@webzero.it', 'Information');
		$mail->addCC('info@webzero.it');
		
		
		
		$mail->isHTML(true); 
		
				
		$mail->Subject = 'Oggetto Mail';
		$mail->Body    = 'Cormpo in <b>HTML</b> <i>Ciao!</i>';
		$mail->AltBody = 'Corpo in plaintext';
		
		if(!$mail->send()) {
			echo 'Messaggio non inviato';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Messaggio inviato';
		}		
	}
	
	
}
?>