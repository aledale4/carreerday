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
                <input type="password" id="password" name="password" required placeholder="Password">
                <input type="password" id="password-confirm" name="password-confirm" required placeholder="Password confirm">
                <input type="hidden" name="pag" value="reset_pwd">
                <input type="submit" value="Reset Password">
            </form>
        </div>
      
    </div>
</body>
</html>