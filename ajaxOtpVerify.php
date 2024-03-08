<?php
session_start();
$hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'hbwebsite';
$connn = new mysqli($hname,$uname,$pass,$db);

$number1 = $_POST['otp'];

$otp = $number1;
$Emailid = $_SESSION['Emailid'];
// Fetch the OTP from the database

$query = "SELECT * FROM otp WHERE email = '$Emailid'";
$result = $connn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $storedOTP = $row['otp'];
        if ($otp == $storedOTP) {
            $sql = "UPDATE `user_cred` SET `is_verified`='1' WHERE `email` = '$Emailid'";
            $connn->query($sql);
            $sql = "DELETE FROM otp WHERE email =  '$Emailid'";
            mysqli_query($connn, $sql);
            echo "<script>window.location.href='index.php'</script>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Invalid OTP.</div>";
        }
    }
}
