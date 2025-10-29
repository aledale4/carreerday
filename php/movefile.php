<div class="home-container">
    <div class="navbar">
        <div class="left-side">
            <img src="../static/logo.svg" alt="" srcset="" class="logo">
            <p>Portale <?php
            switch ($_SESSION["user-type"]) {
                case 1:
                    echo "Admin";
                    break;
                case 2:
                    echo "Studenti";
                    break;
                case 3:
                    echo "Aziende";
                    break;
            } ?></p>
        </div>
    </div>
    <div class="title-bar">
        <a href="index.php"><span class="material-symbols-outlined">arrow_back_ios_new</span></a>
        <p>Carica immagine profilo</p>
    </div>
</div>
<div class="zap">
    <form action="index.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="pag" value="fotoprofilo2" >
        <h3>Scegli la tua immagine profilo</h3>
        <input type="file" name="miofile" id="" accept=".jpeg, .jpg, .png" required>
        <input type="submit" value="PROCEDI" class="">
    </form>

</div>
</div>
