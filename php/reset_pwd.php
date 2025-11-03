<div class="container_reset_pwd">
    <h1>
    Reset Password
    </h1>
    <div class="form_container_reset_pwd">
        <form action="index.php" method="post" class="form_reset_pwd">
            <p>Password Temporanea
            <input type="password" id="password" name="password_temp" placeholder="A1Bcd£ftG56gGuuj" required>
            </p>
            <p>Inserisci una password:
            <input type="password" id="password" name="password1" placeholder="Password1234" required>
            </p>
            <p>Inserisci di nuovo la password:
            <input type="password" id="password-confirm" name="password2" placeholder="Password1234" required>
            </p>
            <input type="hidden" name="pag" value="reset_pwd">
            <input type="hidden" name="token" value="<?php echo $_GET["token"]; ?>">
            <input type="submit" value="Reset Password">
        </form>
    </div>
</div>