<div class="container_send_mail">
    <div class="container_form_send_mail">
        <div class="title">
            <a href="index.php"><span class="material-symbols-outlined">arrow_back_ios_new</span></a>
            <h1>Richiedi Reset Password</h1>
        </div>
        <form action="index.php" method="post">
            <input type="email" id="email" name="email" placeholder="Email" maxlength="100" required>
            <input type="hidden" name="pag" value="request_reset_pwd">
            <input type="submit" value="Invia Email">
        </form>
    </div>
</div>