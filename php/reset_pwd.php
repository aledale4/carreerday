<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reset_password.css">
    <title>Reset-password</title>
</head>
<body>
    <div class="container_reset_pwd">
        <h1>
            Reset Password
        </h1>
        <div class="form_container_reset_pwd">
            <form action="reset-password.php" method="post" class="form_reset_pwd">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <input type="password" id="password-confirm" name="password-confirm" placeholder="Password confirm" required>
                <input type="hidden" name="pag" value="reset_pwd">
                <input type="submit" value="Reset Password">
            </form>
        </div>
      
    </div>
</body>
</html>