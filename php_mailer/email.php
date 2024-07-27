<?php

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug  = SMTP::DEBUG_OFF;
    $mail->isSMTP(); // Send using SMTP
    $mail->Host       = ''; // Set the SMTP server to send through
    $mail->SMTPAuth   = true; // Enable SMTP authentication
    $mail->Username   = ''; // SMTP username
    $mail->Password   = ' '; // SMTP password
    $mail->SMTPSecure = '';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587; // TCP port to connect to

   // Recipients
   $name = $_POST['name'];
   $userEmail = $_POST['email'];
   $message = $_POST['message'];

    // Check the honeypot field to prevent spam
    if (!empty($_POST['honeypot'])) {
        // It's likely a bot; you can log this or handle it as needed.
        die("Spam detected.");
    }

    // Verify the reCAPTCHA response
    $recaptchaSecretKey = '-'; // Replace with your secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $recaptchaVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse";
    $recaptchaResponseData = json_decode(file_get_contents($recaptchaVerifyUrl));

    if (!$recaptchaResponseData->success) {
        // reCAPTCHA verification failed
        echo "reCAPTCHA verification failed. Please try again.";
    } else {
        // reCAPTCHA verification succeeded, proceed with form processing
        // ... Your email sending code goes here ...
        echo "Message sent successfully, please check your email."; // You can customize this success message.
    }


    // Continue processing the form as before.


   // Set the sender's email and name (from the user)
   $mail->setFrom($userEmail, $name);

   // Recipient (Galactic Investments)
   $mail->addAddress('', 'Galactic Investments');

   // Subject
   $subject = 'New Request from ' . $name;

   // Create a professional-looking email message
   $message = '
       <html>
       <head>
           <title>' . $subject . '</title>
       </head>
       <body>
           <p>Dear Galactic Investments Support Team,</p>
           <p>You have received a new request from <strong>' . $name . '</strong>:</p>
           <p><strong>' . $message . '</strong></p>
           <p>Please respond promptly to this request.</p>
           <br>
           <p>Best regards,<br>Galactic Investments</p>
           <hr style="border: 1px solid #ccc; margin-top: 20px;">
           <p style="font-size: 12px; color: #777;">This email is sent via the Galactic Investments website. Please do not reply to this email.</p>
       </body>
       </html>
   ';

   // Content
   $mail->isHTML(true); // Set email format to HTML
   $mail->Subject = $subject;
   $mail->Body    = $message;

   // Set the reply-to address to the user's email
   $mail->addReplyTo($userEmail, $name);

   $mail->send();



$userMessage = '
<html>
<head>
    <title>' . $subject . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0e4157;
            padding: 20px;
            margin: 0;
        }
        .header {
            background-color: #000;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px #888888;
            margin: 0 auto;
            text-align: center;
        }
        .header img {
            max-width: 150px;
            margin: 20px;
            border-radius: 10px;
        }
        .content {
            background-color: #f0f0f0;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px #888888;
            margin: 20px auto;
            padding: 20px;
        }
        .footer {
            border-top: 1px solid #777;
            margin-top: 20px;
            padding-top: 20px;
            text-align: center;
        }
        .social-link {
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://cdn.discordapp.com/attachments/1084600998070321265/1175452420025884762/image.png?ex=656b485f&is=6558d35f&hm=3522baf551432099c2e053bed76c203c609f555172fdf72de47b9b801ee699b7&" alt="Company Logo">
    </div>
    <div class="content">
        <p style="color: #333;">Dear ' . $name . ',</p>
        <p style="color: #333;">Thank you for contacting Galactic Investments. Your message has been received and is important to us. We will get back to you shortly.</p>
        <p style="color: #333;">Please feel free to contact us if you have any further questions or concerns.</p>
        <br>
        <p style="color: #333;">Best regards,<br>Galactic Investments</p>
    </div>
    <div class="footer">
        <hr style="border: 1px solid #777; margin-bottom: 20px;">
        <p style="color: #777; font-size: 12px;">Follow us on social media:</p>
        <a href="#" class="social-link"><img src="social_facebook_logo.png" alt="Facebook"></a>
        <a href="#" class="social-link"><img src="social_twitter_logo.png" alt="Twitter"></a>
        <a href="#" class="social-link"><img src="social_instagram_logo.png" alt="Instagram"></a>
    </div>
</body>
</html>
';
   
   
   
   $mail->ClearAddresses();
   
   // Set the sender's email and name explicitly
   $mail->setFrom('', 'Galactic');
   $mail->addAddress($userEmail);
   
   $mail->Subject = "Re: " . $subject;
   $mail->Body = $userMessage;
   $mail->send();



} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}