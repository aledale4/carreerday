<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="send_mail.css">
    <title>Send-mail</title>
</head>
<body>
    <div class="container">
        <h1>
        Send Mail   
        </h1>
        <div class="form-container">
        <form action="index.php" method="POST">
            <label for="email"></label>
            <input type="email" id="email" name="email" placeholder="email" required>
            <br><br>
            <input type="hidden" name="pag" value="request_reset_pwd">
            <input type="submit" value="Send Mail">
        </form>
        </div>
    </div>
</body>
</html>