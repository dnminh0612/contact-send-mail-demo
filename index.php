<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

if ($_POST) {
    $user_name = "";
    $user_email = "";
    $email_title = "";
    $subject = "";
    $message = "";
    $validate = true;

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $input['name'] = $name;
    if (!$name || trim($name) === '') {
        $errors['name'] = 'Please enter your name';
        $validate = false;
    }

    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $input['phone'] = $phone;
    if (!$phone || trim($phone) === '') {
        $errors['phone'] = 'Please enter your phone';
        $validate = false;
    } elseif (!preg_match('/(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})\b/', $phone)) {
        $errors['phone'] = 'Invalid phone number';
        $validate = false;
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $input['email'] = $email;
    if ($email) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!$email) {
            $errors['email'] = 'Please enter an email';
            $validate = false;
        }elseif (!preg_match('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/', $email)) {
            $errors['email'] = 'Please enter a valid email';
            $validate = false;
        }
    } else {
        $errors['email'] = 'Please enter an email';
        $validate = false;
    }

    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $input['subject'] = $subject;
    if (!$subject || trim($subject) === '') {
        $errors['subject'] = 'Please enter the subject';
        $validate = false;
    }

    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $input['message'] = $message;
    if (!$message || trim($message) === '') {
        $errors['message'] = 'Please enter the message';
        $validate = false;
    }
    if ($validate === true){
        $mail = new PHPMailer();
        try {
            $mail->IsSMTP();
            $mail->CharSet = 'UTF-8';

            $mail->Host = "smtp.gmail.com";
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->Port = 587;
            $mail->Username = "email4testvn@gmail.com";
            $mail->Password = "yzxkbxkicdrmhzmj";

            $mail->setFrom('email4testvn@gmail.com', 'Test');
            $mail->addAddress($input['email'], $input['name']);
            $mail->isHTML(true);
            $mail->Subject = $input['subject'];
            $mail->Body = $input['message'];

            $mail->send();
            $success['message'] = "Send mail success";

        } catch (Exception $e) {
            echo "error send mail";
        }
    }


}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .form-group {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>


<form action="/" method="post">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" placeholder="Enter your name" value="<?= $input['name'] ?? '' ?>">
        <small><?= $errors['name'] ?? '' ?></small>
    </div>

    <div class="form-group">
        <label for="name">Phone</label>
        <input type="text" name="phone" placeholder="Phone number" value="<?= $input['phone'] ?? '' ?>">
        <small><?= $errors['phone'] ?? '' ?></small>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="abc@email.com" value="<?= $input['email'] ?? '' ?>">
        <small><?= $errors['email'] ?? '' ?></small>
    </div>

    <div class="form-group">
        <label for="name">Subject</label>
        <input type="text" name="subject" placeholder="Subject" value="<?= $input['subject'] ?? '' ?>">
        <small><?= $errors['subject'] ?? '' ?></small>
    </div>

    <div class="form-group">
        <label for="name">Message</label>
        <textarea name="message" placeholder="Message"><?= $input['message'] ?? '' ?></textarea>
        <small><?= $errors['message'] ?? '' ?></small>
    </div>

    <div class="form-group">
        <?= $success['message'] ?? '' ?>
    </div>

    <div class="g-recaptcha" data-sitekey="6Ld1PNofAAAAAGCel9rbZ6K6SxWyFSieLsv_l_8W"></div>

    <button type="submit">Send Message</button>
</form>

</body>
</html>
