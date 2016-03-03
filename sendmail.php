<html>

<head>
    <link rel="stylesheet" href="css/stylesheet.css">
    <meta http-equiv="Content-Type" content="text/html; charset=US-ASCII">
    <?php include 'functionsxml.php' ?>
    <title>Webinterface firewall - Mail</title>
</head>

<body>

<div class="container">
    <header>
        <h1>Webinterface firewall</h1>
    </header>
    <main></main>
    <div id="marginleftright">

        <?php

        $messageforsupport = $_POST["messageforsupport"];
        $emailsender = $_POST["emailsender"];
        $originalcommand = $_POST["originalcommand"];
        $contentscommand = $_POST["contentscommand"];
	$html=$_POST["htmlvar"];
	$sourcereverse=$_POST["sourcereversename"];
	$destreverse=$_POST["destreversename"];
        
require 'PHPMailer/PHPMailerAutoload.php';


        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPDebug = false;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->Username = "eindwerk.firewall.ucll@gmail.com";
        $mail->Password = "flipflopqwark7";
        $mail->isHTML(true);

        $mail->From = "eindwerk.firewall.ucll@gmail.com";
        $mail->FromName = "firewall ucll";
        $mail->AddAddress("eindwerk.firewall.ucll@gmail.com");

	$contentsCommandInhtml .= "source name: ".$sourcereverse." <BR> destination name: ".$destreverse;
        $contentsCommandInhtml .= "<br><br>";
        $contentsCommandInhtml .= $originalcommand;
        $contentsCommandInhtml .= "<br><br>";
	$contentsCommandInhtml .=$html;
        $mail->Subject = "Webinterface firewall: " . $emailsender;
        $mail->Body = $css . $messageforsupport . "<br><br>" . $header . $contentsCommandInhtml . $css;

        if (!$mail->send()) {
            echo '<h1>Message could not be sent.</h1>';
            echo '<p><Mailer Error: ' . $mail->ErrorInfo . '</p>';
        } else {
            echo '<h1>Message has been sent</h1>';
        }
        ?>

