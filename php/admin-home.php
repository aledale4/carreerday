<div class="home-container">

    <div class="navbar">
        <div class="left-side">
            <img src="../static/logo.svg" alt="" srcset="" class="logo">
            <p>Portale Admin</p>
        </div>
        <div class="right-side">
            <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeUt"]; ?></span></p>
            <a href="index.php?pag=settings">
                <div class="user-pic"><?php include("defaultUser-pic.php") ?></div>
            </a>
            <a href="index.php?pag=logout" class="logout"><span class="material-symbols-outlined logout-icon">logout</span></a>
        </div>
    </div>

<section id="eventi">
    <h1>Eventi</h1>
    <div class="scrollable-container">
        <a href="index.php?pag=new_event">
            <div class="element">
                <span class="material-symbols-outlined">add</span>
                <p>Nuovo Evento</p>
            </div>
        </a>
        <?php 
            $q = "select * from career_day";
            $r = mysqli_query($conn, $q);
            while ($row = mysqli_fetch_assoc($r)) {
                echo '<a href="index.php?pag=event&id='.$row["idCd"].'">';
                echo '<div class="element">';
                echo '<img src="" alt="logo evento">';
                echo '<p>'.$row["nameCd"].'</p>';
                echo '</div></a>';
            }
        ?>
    </div>
</section>


</div>