<?php

/**
 * Created by PhpStorm.
 * User: rayanelhajj98
 * Date: 7/06/2017
 * Time: 5:31 PM
 */

class Mailer
{
    private $username="unterdevelopmentteam@gmail.com" , $password= "UnterApplication1234";

    function sendMail($toAdd,$subject,$body){
        //initializing a new PHPMailer Object
        $mail = new PHPMailer();
        //enabling SMTP
        //$mail->IsSMTP();
        //the value 1 is for both errors and messages, 2 is for messages only
        $mail->SMTPDebug=1;
        //enabling authentication
        $mail->SMTPAuth=true;
        //enabling secure transfer, it's required for gmail
        $mail->SMTPSecure='ssl';
        //the host name for gmail
        $mail->Host='smtp.gmail.com';
        //this is not the only port that can be used
        $mail->Port= 587;
        $mail->isHTML(true);
        $mail->Username="unterdevelopmentteam@gmail.com";
        $mail->Password="UnterApplication1234" ;
        $mail->setFrom("unterdevelopmentteam@gmail.com");
        $mail->Subject=$subject;
        $mail->Body=$body;
        $mail->AddAddress($toAdd);

        if(!$mail->send()){
            echo "Error".$mail->ErrorInfo;
        }else{
            echo "Successssssss";
        }
    }
}