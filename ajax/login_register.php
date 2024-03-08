<?php

use PHPMailer\PHPMailer\PHPMailer;

session_start();
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
require("../PHPMailer/Exception.php");
require("../PHPMailer/PHPMailer.php");
require("../PHPMailer/SMTP.php");

date_default_timezone_set("Asia/Kolkata");

function sendMail($email, $otp)
{

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'hotelsbooking8386@gmail.com';                     //SMTP username   quickcarhire.india@gmail.com
        $mail->Password = 'fhzkavlgdsugybcd';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = phpmailer::ENCRYPTION_STARTTLS`
//Recipients
        $mail->setFrom('hotelsbooking8386@gmail.com', 'OTP Verification ');
        $mail->addAddress($email);     //Add a recipient

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'OTP verification Quick car hire';
        $mail->Body = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div
        style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
    <div style="margin:50px auto;width:70%;padding:20px 0">
        <div style="border-bottom:1px solid #eee">
            <a href
               style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">
                Hotel Booking</a>
        </div>
        <p style="font-size:1.1em">Hi,</p>
        <p>Thank you for HB. Use the following OTP to
            complete your Sign Up procedures. OTP is valid for 5 minutes</p>
        <h2
                style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">' . $otp . '</h2>
        <p style="font-size:0.9em;">Regards,<br />Hotel Booking System</p>
        <hr style="border:none;border-top:1px solid #eee" />
        <div
                style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
            <p>HB</p>
            <img src="cid:image_cid" alt="car" height="50" width="50">
        </div>
    </div>
</div>
</body>
</html>';
//        $imageData = file_get_contents('http://localhost/quickcarhire/Customer/logo.png');
//        $mail->addStringEmbeddedImage($imageData, 'image_cid', 'logo.png');
        $mail->send();
//        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "<script> alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
    }
}


if(isset($_POST['register'])){
    $email = $_POST['email'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phonenum'];
    $pin = $_POST['pincode'];
    $dob = $_POST['dob'];
    $img = uploadUserImage($_FILES['profile']);
    $enc_pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
    $hname = 'localhost';
    $uname = 'root';
    $pass = '';
    $db = 'hbwebsite';
    $connn = new mysqli($hname,$uname,$pass,$db);
    $otp = rand(1000000, 9999999);
    $sql = "INSERT INTO `otp`(`email`, `otp`) VALUES ('$email','$otp')";
    $connn->query($sql);
    $currentDateTime = date("Y-m-d H:i:s");
    $sql = "INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `pincode`, `dob`, `profile`, `password`, `is_verified`, `status`, `datentime`) 
    VALUES ('$name','$email','$address','$phone','$pin','$dob','$img','$enc_pass','0','1','$currentDateTime')";
    $connn->query($sql);
    sendMail($email,$otp);
    $_SESSION['Emailid'] = $email;
    echo "<script>window.location.href = '../otpVerification.php'</script>";
//    echo "<script>
//    // This script will trigger the modal to show on page load
//    document.addEventListener('DOMContentLoaded', function () {
//        var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
//        myModal.show();
//    });
//    </script>";
}

if (isset($_POST['login'])) {
    $data = filteration($_POST);

    $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? OR `phonenum`=? LIMIT 1",
        [$data['email_mob'], $data['email_mob']], "ss");

    if (mysqli_num_rows($u_exist) == 0) {
        echo 'inv_email_mob';
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if ($u_fetch['is_verified'] == 0) {
            echo 'not_verified';
        } else if ($u_fetch['status'] == 0) {
            echo 'inactive';
        } else {
            if (!password_verify($data['pass'], $u_fetch['password'])) {
                echo 'invalid_pass';
            } else {
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['uId'] = $u_fetch['id'];
                $_SESSION['uName'] = $u_fetch['name'];
                $_SESSION['uPic'] = $u_fetch['profile'];
                $_SESSION['uPhone'] = $u_fetch['phonenum'];
                echo 1;
            }
        }
    }
}

if (isset($_POST['forgot_pass'])) {
    $data = filteration($_POST);

    $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1", [$data['email']], "s");

    if (mysqli_num_rows($u_exist) == 0) {
        echo 'inv_email';
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if ($u_fetch['is_verified'] == 0) {
            echo 'not_verified';
        } else if ($u_fetch['status'] == 0) {
            echo 'inactive';
        } else {
            // send reset link to email
            $token = bin2hex(random_bytes(16));

            if (!send_mail($data['email'], $token, 'account_recovery')) {
                echo 'mail_failed';
            } else {
                $date = date("Y-m-d");

                $query = mysqli_query($con, "UPDATE `user_cred` SET `token`='$token', `t_expire`='$date' 
            WHERE `id`='$u_fetch[id]'");

                if ($query) {
                    echo 1;
                } else {
                    echo 'upd_failed';
                }
            }
        }
    }

}

if (isset($_POST['recover_user'])) {
    $data = filteration($_POST);

    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

    $query = "UPDATE `user_cred` SET `password`=?, `token`=?, `t_expire`=? 
      WHERE `email`=? AND `token`=?";

    $values = [$enc_pass, null, null, $data['email'], $data['token']];

    if (update($query, $values, 'sssss')) {
        echo 1;
    } else {
        echo 'failed';
    }
}

?>