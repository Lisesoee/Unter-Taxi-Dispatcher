<?php

/**
 * Created by PhpStorm.
 * User: rayanelhajj98
 */
require ('C:\xampp\htdocs\PHPMailer\PHPMailerAutoload.php');

/**
 * Class Mailer
 * sends emails using the PHPMailer library
 */
class Mailer
{
     const USERNAME = "unterdevelopmentteam@gmail.com", PASSWORD = "UnterApplication1234",
        PORT = 25,
        HOST_NAME= 'smtp.gmail.com';

    /**
     * sends an email to the provided email address with the provided subject and body message
     * uses the PHPMailer library & gmail smtp server
     * uses tls
     * prints out in case of error or success
     * @param $toAdd
     * @param $subject
     * @param $body
     */
    public function sendMail($toAdd, $subject, $body)
    {
        //initializing a new PHPMailer Object
        $mail = new PHPMailer();
        //enabling SMTP
        $mail->IsSMTP();
        //used the value 3 to show connection status, client -> server and server -> client messages, lowest is 0 for no debugging messages
        $mail->SMTPDebug = 3;
        //enabling authentication
        $mail->SMTPAuth = true;
        //in order to avoid the openssl issue that wasn't solved locally on the developer's pc, these codes had to be added
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        //enabling secure transfer, it's required for gmail, using tls
        $mail->SMTPSecure = 'tls';
        //the host name for gmail
        //the method gethostbyname is to get the IPv4 address corresponding to a given Internet host name
        $mail->Host = gethostbyname(Mailer::HOST_NAME);
        //this is not the only port that can be used -- this is for tls -- for ssl try 25 or 467
        $mail->Port =Mailer::PORT;
        //disabling the encryption in case the certificate at the server side is not valid
        $mail->SMTPAutoTLS=false;
        //sending plain text-- setting then the isHTML to false
        $mail->isHTML(false);
        //authentication username and password for the unter taxi development team
        $mail->Username = Mailer::USERNAME; //"unterdevelopmentteam@gmail.com";
        $mail->Password = Mailer::PASSWORD; //"UnterApplication1234";
        //setting the from field in the mail message
        $mail->setFrom(Mailer::USERNAME);
        //setting the subject to the subject provided as argument
        $mail->Subject = $subject;
        //setting the body to the message provided as an argument
        $mail->Body = $body;
        //setting the destination address -- i.e. the email address to receive the mail message
        $mail->AddAddress($toAdd);

        if (!$mail->send()) {
            //if the send operation fails printing out the error
            echo "Error" . $mail->ErrorInfo;
        } else {
            //if the mail is sent successfully printing out the success
            echo "Successssssss";
        }
    }
}