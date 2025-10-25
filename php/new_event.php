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
        </div>
    </div>

    <div class="event">
        <div class="event-title">
            <a href="index.php"><span class="material-symbols-outlined">arrow_back_ios_new</span></a>
            <p>Creazione Nuovo Evento</p>
        </div>
        <form action="index.php" method="post">
            <div class="event-data-inputs">
                <input type="hidden" name="pag" value="new_event">
                <p>Nome dell'evento: <input maxlength="30" type="text" name="nome" id="" placeholder="" required></p>
                <p>Descrizione: <textarea maxlength="256" name="descrizione" id="" required></textarea></p>
                <p>Quando? <input type="date" name="date" id="" required></p>
                <p>Orario di inizio: <input type="time" name="start_time" id="" required></p>
                <p>Orario di fine <input type="time" name="end_time" id="" required></p>
                <p>Luogo: <input maxlength="30" type="text" name="pos" id="" placeholder="" required></p>
                <input type="submit" value="Crea">
            </div>
            <table class="companies-choice-table">
                <tr><th>Nome Azienda</th><th>Seleziona</th></tr>
                <?php
                    $q = "select * from aziende";
                    $r = mysqli_query($conn, $q);
                    while ($row = mysqli_fetch_assoc($r)) {
                        echo "<tr>";
                        echo "<td>".$row["ragsoc"]."</td>";
                        echo '<td><input type="checkbox" name="'.$row["idAz"].'"></td>';
                        echo "</tr>";
                    }
                ?>
            </table>
        </form>
    </div>
</div>