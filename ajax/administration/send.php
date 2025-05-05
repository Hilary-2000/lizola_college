<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';


    if (isset($_POST['send'])) {

        try {
            $mail = new PHPMailer(true);
    
            $mail->isSMTP();
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            // $mail->Host = 'smtp.gmail.com';
            $mail->Host = 'mail.privateemail.com';
            $mail->SMTPAuth = true;
            // $mail->Username = "hilaryme45@gmail.com";
            // $mail->Password = "cmksnyxqmcgtncxw";
            $mail->Username = "mail@ladybirdsmis.com";
            $mail->Password = "2000Hilary";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587;
            
            
            $mail->setFrom("mail@ladybirdsmis.com","Ladybird Softech Co");
            $mail->addAddress($_POST['email']);
            $mail->isHTML(true);
            $mail->Subject = $_POST['subject'];
            $mail->Body = $_POST['message'];
    
            $mail->send();
            
            echo 
            "
                <script>
                    alert('Message sent successfully');
                    document.location.href = 'index.php';
                </script>
            ";
        } catch (Exception $th) {
            echo "Exception is : ". $mail->ErrorInfo;
        }

    }

?>