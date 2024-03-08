<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<?php require('inc/links.php'); ?>

<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>OTP Verification Form</title>
        <link rel="stylesheet" href="common.css" />
        <!-- Boxicons CSS -->
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
        <script src="script.js" defer></script>
        <style>
            .alert {
                padding: 15px;
                border: 1px solid transparent;
                border-radius: 4px;
            }

            .alert-danger {
                color: #721c24;
                background-color: #f8d7da;
                border-color: #f5c6cb;
            }
            .container {
                width: 400px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ccc;
            }

            header {
                margin-bottom: 20px;
            }

            h4 {
                font-size: 18px;
                font-weight: bold;
            }

            .input-field {
                margin-bottom: 10px;
            }

            input {
                width: 400px;
                height: 40px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            button {
                background-color: #007bff;
                color: white;
                font-size: 16px;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
    <?php require('inc/header.php'); ?>

    <div class="container" style="height: 600px;">
            <header>
                <i class="bx bxs-check-shield"></i>
            </header>
            <h4>Enter OTP Code</h4>
            <div id="result"></div>
            <form method="post"  id="otpForm" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">OTP</label>
                        <input type="hidden" name="register" value="hi">
                        <input name="otp" type="number" class="form-control shadow-none" required>

                    </div>
                </div>

                <div class="input-field">

                </div>
                <button type="submit">Verify OTP</button>

            </form>
        </div>


    </body>
    <?php require('inc/footer.php'); ?>

</html>
