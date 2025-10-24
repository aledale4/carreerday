<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="send_mail.css">
    <title>Send-mail</title>
</head>
<body>
    <div class="container_send_mail">
        <h1>
        Send Mail   
        </h1>
        <div class="container_form_send_mail">
        <form action="index.php" method="post" class="form_send_mail">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="hidden" name="pag" value="request_reset_pwd">
            <input type="submit" value="Send Mail">
        </form>
        </div>
    </div>
</body>
</html>