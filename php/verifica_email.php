<?php

function generateVerificaEmailLogin($tok) {
    ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style type="text/css">
        body{
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }
        .reset-pwd-email-container{
            max-width: 600px;
            margin: 50px auto; 
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .reset-pwd-email-container img{
            display: block;
            margin: 0 20px;
            max-width: 150px;
            width: 100%;
        }
        .reset-pwd-email-container a{
            text-decoration: none;
            display: inline-block;
            color: black;
            height: fit-content;
            margin: 10px 0;
            padding: 20px;
            background-color: rgba(250,160,0,1);
            font-weight: 600;
            font-size:1.5rem;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="reset-pwd-email-container">
        <img src="https://careerday.altervista.org/static/logo.png" alt="Logo" >
        <h1>Verifica Email</h1>
        <p>Per verificare il tuo account, clicca sul link sottostante:</p>
        <a target="_blank" href="https://careerday.altervista.org/php/index.php?pag=conferma_email&token=<?php echo $tok; ?>">VERIFICA</a>
        <p>Se non hai creato un account, ignora questa email.</p>
        <p>Grazie,<br>Il team di Career Day</p>
    </div>
</body>
</html>

<?php
    $email_content = ob_get_clean();
    return $email_content;
}
?>