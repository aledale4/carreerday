<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reset_password.css">
    <title>Reset-password</title>
</head>
<body>
    <div class="container">
        <h1>
            Reset Password
        </h1>
        <div class="form-container">
            <form action="index.php" method="POST">
                <p>password temporanea</p>
                <input type="password" id="password" name="password_temp" required>
                <br>
                <label for="password">Password:</label>
                
                <input type="password" id="password" name="password1" required>
                <br>
                <label for="password-confirm">Password confirm:</label>
                <input type="password" id="password-confirm" name="password2" required>
                <br><br>
                <input type="hidden" name="pag" value="reset_pwd">
                <input type="hidden" name="token" value="<?php echo $_GET["token"]; ?>">
                <input type="submit" value="Reset Password">
                
            </form>
        </div>
      
    </div>
</body>
</html>