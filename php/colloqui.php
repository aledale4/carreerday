<div class="home-container">

    <div class="navbar">
        <div class="left-side">
            <a href="index.php" class="logo"><img src="../static/logo.png" alt="" srcset=""></a>
            <p>Portale Aziende</p>
        </div>
        <div class="middle-nav">
            <div class="nav-page">
                <a href="index.php"><p>Eventi</p></a>
            </div>
            <div class="nav-page selected" >
                <a href="index.php?pag=colloqui"><p>Colloqui</p></a>
            </div>
            <div class="nav-page">
                <a href="index.php?pag=posizioni"><p>Posizioni</p></a>
            </div>
        </div>
        <div class="right-side">
            <p>Benvenuto/a, <span><?php echo $_SESSION["user"]["nomeRef"]; ?></span></p>
             <a href="index.php?pag=settings">
                <div class="user-pic"><?php include("defaultUser-pic.php")  ?></div>
            </a>
            <div class="suggestion">Modifica il tuo profilo <img src="../static/arrow.svg" alt=""></div>
            <a href="index.php?pag=logout" class="logout"><span class="material-symbols-outlined logout-icon">logout</span></a>
        </div>
    </div>

    <div class="mobile-nav">
        <div class="nav-page">
            <a href="index.php"><p>Eventi</p></a>
        </div>
        <div class="nav-page selected">
            <a href="index.php?pag=colloqui"><p>Colloqui</p></a>
        </div>
        <div class="nav-page">
            <a href="index.php?pag=posizioni"><p>Posizioni</p></a>
        </div>
    </div>

    <section id="colloquiPren">
        <h1>Colloqui Prenotati</h1>
        <div class="colloqui">
            <div class="elenco <?php echo (isset($_GET["selected"])&&$_GET["selected"]!='')?'collapsed':''?>">
            <p>Filtra per evento:</p>
            <select name="evento" id="selectEvent" class="shadow-s">
                <option value="-1">Tutti</option>
                <?php 
                    $q = "select * from adesioni where rAz = ".$_SESSION["user"]["idAz"];
                    $r = mysqli_query($conn, $q);
                    $i = 0;
                    while ($adesione = mysqli_fetch_assoc($r)) {
                        $qEv = "select * from career_day where idCd = ".$adesione["rCd"];
                        $rEvPren = mysqli_query($conn, $qEv) or die();
                        if (mysqli_num_rows($rEvPren) == 0) exit();
                        $evento = mysqli_fetch_assoc($rEvPren);
                        echo "<option value='".$i."'>".$evento["nameCd"]."</option>";
                        $i++;
                    }
                ?>
            </select>
            <p>Filtra per posizione:</p>
            <select name="posizione" id="selectPosition" class="shadow-s">
                <option value="-1">Tutti</option>
                <?php 
                    $posQ = "select * from posizioni where rAz = ".$_SESSION["user"]["idAz"];
                    $posPren = mysqli_query($conn, $posQ) or die();
                    while ($posizione = mysqli_fetch_assoc($posPren)) {
                        echo "<option value='".$posizione["idPos"]."'>".$posizione["nomePos"]."</option>";
                    }
                ?>
            </select>
            <div class="onlyCompletedCheck">
                <p>Solo completati:</p>
                <input type="checkbox" id="onlyCompleted">
            </div>
            <p>Filtra per valutazione</p>
            <div class="starFilter">
                <div class="startFilterBt">
                    <input type="radio" id="0starfilter" name="star-filter" data-value="0">
                    <label for="0starfilter"><span class="material-symbols-outlined star" data-value="1">star_rate</span> Tutti</label>
                </div>
                <div class="startFilterBt">
                    <input type="radio" id="1starfilter" name="star-filter" data-value="1">
                    <label for="1starfilter"><span class="material-symbols-outlined star" data-value="1">star_rate</span> 1+</label>
                </div>
                <div class="startFilterBt">
                    <input type="radio" id="2starfilter" name="star-filter" data-value="2">
                    <label for="2starfilter"><span class="material-symbols-outlined star" data-value="1">star_rate</span> 2+</label>
                </div>
                <div class="startFilterBt">
                    <input type="radio" id="3starfilter" name="star-filter" data-value="3">
                    <label for="3starfilter"><span class="material-symbols-outlined star" data-value="1">star_rate</span> 3+</label>
                </div>
                <div class="startFilterBt">
                    <input type="radio" id="4starfilter" name="star-filter" data-value="4">
                    <label for="4starfilter"><span class="material-symbols-outlined star" data-value="1">star_rate</span> 4+</label>
                </div>
                <div class="startFilterBt">
                    <input type="radio" id="5starfilter" name="star-filter" data-value="5">
                    <label for="5starfilter"><span class="material-symbols-outlined star" data-value="1">star_rate</span> 5</label>
                </div>
            </div>
            <p>Cerca studente:</p>
            <input type="text" placeholder="Cerca..." id="searchBar" class="shadow-s">
            <?php
                mysqli_data_seek($r,0);
                while ($adesione = mysqli_fetch_assoc($r)) {
                    $qEv = "select * from career_day where idCd = ".$adesione["rCd"];
                    $rEvPren = mysqli_query($conn, $qEv) or die();
                    if (mysqli_num_rows($rEvPren) == 0) exit();
                    $q2 = "select * from prenotazioni where rAd=".$adesione["idAd"]." order by idPren";;
                    $rPren = mysqli_query($conn, $q2) or die();
                    $evento = mysqli_fetch_assoc($rEvPren);
                    echo "<div class='evento-lista-stu'>";
                    echo "<p class='evento-colloquio'>".$evento["nameCd"]."</p>";
                    echo "<table><tr><th>Completato</th><th>Studente</th><th>Posizione</th></tr>";//<th>Posizione</th><th>Data prenotazione</th>
                    $progressivo = 1;
                    while ($prenotazione = mysqli_fetch_assoc($rPren)) {
                        $qStu = "select * from studenti where idStu = ".$prenotazione["rStu"];
                        $rStu = mysqli_query($conn, $qStu) or die();
                        if (mysqli_num_rows($rStu) == 0) continue;
                        $stu = mysqli_fetch_assoc($rStu);
                        $nomeStu = $stu["nomeStu"]." ".$stu["cognomeStu"];

                        $posQ = "select * from posizioni where idPos = ".$prenotazione["rPos"];
                        $posPren = mysqli_query($conn, $posQ) or die();
                        $pos = [];
                        if (mysqli_num_rows($posPren) != 0){
                            $pos = mysqli_fetch_assoc($posPren);
                        }
                        echo "<tr class='studente ".($prenotazione["completed"]==1?"checked":"")." ".($_GET["selected"]==$prenotazione["idPren"]?"selected":"")."' data-pos='".$pos["idPos"]."' data-val='".$prenotazione["valutazionePren"]."'><td>";
                        echo "<form action='index.php' method='post'>";
                        echo '<input type="hidden" name="id" value="'.$prenotazione["idPren"].'">';
                        echo '<input type="hidden" name="pag" value="update_prenotazione">';
                        echo '<input required type="checkbox" name="completed" onchange="this.form.submit()" '.($prenotazione["completed"]==1?"checked >":">");
                        echo '</form>';
                        echo "</td><td onclick='showOverview(\"".$prenotazione["idPren"]."\")'>";
                        echo "<span class='stu-name'>";
                        echo $nomeStu;
                        echo "</td><td>".$progressivo."</td></span></tr>";
                        $progressivo++;
                    }
                    echo "</table></div>";
                }
            ?>
            </div>
            <div class="overview <?php echo (isset($_GET["selected"])&&$_GET["selected"]!='')?'expanded':''?>">
                <button class="backbt" onclick="back()"><span class="material-symbols-outlined">arrow_back_ios_new</span></button>
                <?php
                $q = "select * from adesioni where rAz = ".$_SESSION["user"]["idAz"];
                $r = mysqli_query($conn, $q);
                while ($adesione = mysqli_fetch_assoc($r)) {
                    $qEv = "select * from career_day where idCd = ".$adesione["rCd"];
                    $rEvPren = mysqli_query($conn, $qEv) or die();
                    if (mysqli_num_rows($rEvPren) == 0) exit();
                    $q2 = "select * from prenotazioni where rAd=".$adesione["idAd"]." order by idPren";;
                    $rPren = mysqli_query($conn, $q2) or die();
                    $evento = mysqli_fetch_assoc($rEvPren);
                    while ($prenotazione = mysqli_fetch_assoc($rPren)) {
                        $qStu = "select * from studenti where idStu = ".$prenotazione["rStu"];
                        $rStu = mysqli_query($conn, $qStu) or die();
                        if (mysqli_num_rows($rStu) == 0) continue;
                        $stu = mysqli_fetch_assoc($rStu);
                        echo "<div class='info-stu shadow-l"." ".($_GET["selected"]==$prenotazione["idPren"]?"expanded":"")."' id='".$prenotazione["idPren"]."'>";
                        $file = '../static/pfp/studente-pic/' . $stu["idStu"] . '.jpeg';
                        echo '<div class="img">';
                        if (file_exists($file)) {
                            $data = file_get_contents($file); 
                            $base64 = base64_encode($data); 
                            if($stu["idStu"] >=1 && $stu["idStu"] <= 3){
                                echo '<img class="gold" src="data:image/jpeg;base64,' . $base64. '" alt="">';
                            }else {
                                echo '<img src="data:image/jpeg;base64,' . $base64. '" alt="">';
                            }
                        } else {
                            if($stu["idStu"] >=1 && $stu["idStu"] <= 3){
                                echo "<img class='gold' src='../static/Default_pfp.svg' alt=''>";
                            }else{
                                echo "<img src='../static/Default_pfp.svg' alt=''>";
                            }
                        }
                        $posQ = "select * from posizioni where idPos = ".$prenotazione["rPos"];
                        $posPren = mysqli_query($conn, $posQ) or die();
                        $pos = [];
                        if (mysqli_num_rows($posPren) != 0){
                            $pos = mysqli_fetch_assoc($posPren);
                        }
                        echo '</div>';
                        echo "<div class='dati'>";
                        echo "<p>Posizione lavorativa: <span>".$pos["nomePos"]."</span></p>";
                        echo "<br>";
                        echo "<p>Nome: <span>".$stu["nomeStu"]."</span></p>";
                        echo "<p>Cognome: <span>".$stu["cognomeStu"]."</span></p>";
                        echo "<p>Email: <span>".$stu["emailStu"]."</span></p>";
                        echo "<p>Numero di telefono: <span>".$stu["telStu"]."</span></p>";
                        echo "<p>Località: <span>".$stu["locStu"]."</span></p>";
                        echo "<br>";
                        echo "<p>Sito web: <span><a target='_blank' href='".$stu["websiteStu"]."'>".$stu["websiteStu"]."</a></span></p>";;
                        echo "<p>GitHub: <span><a target='_blank' href='".$stu["urlGithubStu"]."'>".$stu["urlGithubStu"]."</a></span></p>";
                        echo "<p>LinkedIn: <span><a target='_blank' href='".$stu["urlLinkedinStu"]."'>".$stu["urlLinkedinStu"]."</a></span></p>";
                        echo "<br>";
                        echo "<p>Biografia: <span>".$stu["bioStu"]."</span></p>";
                        echo "<br>";
                        echo "<p>CV: ".(file_exists("../private/cv/".$stu["idStu"].".pdf")?("<a href='index.php?pag=viewcv&id=".$stu["idStu"]."'>Apri</a>"):"<a>No CV</a>")."</p>";
                        echo "<br>";
                        echo "<div class='feedback shadow-s'>";
                        echo "<p>Aggiungi una nota relativa al colloquio:</p>";
                        echo "<form action='index.php' method='post' class='feedbackForm'>";
                        echo "<input type='hidden' name='pag' value='commPren'>";
                        echo "<input type='hidden' name='id' value='".$prenotazione["idPren"]."'>";
                        echo "<textarea class='shadow-s' placeholder=\"Inserisci un commento relativo al colloquio con l'alunno\" maxlength='255' name='feedback'>".$prenotazione["commPren"]."</textarea>";
                        echo "<input type='submit' value='Salva' class=\"shadow-s\">";
                        echo "</form>";
                        echo '</div>';
                        echo '<div class="rating-container shadow-s">';
                        echo '<p>Valuta il colloquio</p>';
                        echo '<form action="index.php" method="post">';
                        echo '<div class="star-rating">';
                        echo '<span class="material-symbols-outlined star" data-value="1">star_rate</span>';
                        echo '<span class="material-symbols-outlined star" data-value="2">star_rate</span>';
                        echo '<span class="material-symbols-outlined star" data-value="3">star_rate</span>';
                        echo '<span class="material-symbols-outlined star" data-value="4">star_rate</span>';
                        echo '<span class="material-symbols-outlined star" data-value="5">star_rate</span>';
                        echo '</div>';
                        echo '<input type="hidden" name="rating" class="ratingValue" value="'.$prenotazione["valutazionePren"].'">';
                        echo '<input type="hidden" name="pag"  value="valutaColloquio">';
                        echo '<input type="hidden" name="id"  value="'.$prenotazione["idPren"].'">';
                        echo '<input type="submit" value="Salva" class="shadow-s">';
                        echo '</form>';
                        echo '</div>';
                        echo "</div>";
                        echo "</div>";
                    }
                }
                ?>
                <p class="hint">Premi sul nome di uno studente per visualizzarne i dati</p>
            </div>
        </div>
    </section>


</div>

<script>

    function back(){
        showOverview(-1);
        let overview = document.querySelector(".colloqui .overview");
        let elenco = document.querySelector(".colloqui .elenco");
        overview.classList.remove("expanded");
        elenco.classList.remove("collapsed");
    }

    function switchInfo(id){
        let info = document.getElementById(id);
        info.classList.toggle("expanded");
    }

    function showOverview(id){
        let overview = document.querySelector(".colloqui .overview");
        let elenco = document.querySelector(".colloqui .elenco");
        let buttons = document.querySelectorAll(".colloqui .elenco .stu-name");
        let infoCards = overview.querySelectorAll(".info-stu");
        overview.classList.add("expanded");
        elenco.classList.add( "collapsed");
        infoCards.forEach(card => {
            if(card.id === id){
                card.classList.add("expanded");
            } else {
                card.classList.remove("expanded");
            }
        });
        buttons.forEach(btn => {
            if(btn.parentNode.getAttribute("onclick") === 'showOverview("'+id+'")'){
                btn.parentNode.parentNode.classList.add("selected");
                console.log(btn);
            } else {
                btn.parentNode.parentNode.classList.remove("selected");
            }
        });
    }

</script>

<script>
        const starsContainers = document.querySelectorAll(".rating-container");

        function updateStars(container){
            var stars = container.querySelectorAll('.star-rating .star');
            var rating = container.querySelector(".ratingValue").value;
            console.log(rating);
            stars.forEach(star =>{
                var starValue = parseInt(star.dataset.value);
                if (starValue <= rating) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
            
        }

        starsContainers.forEach(container => {
            updateStars(container);
            container.addEventListener('click', (event) => {
                var clickedStar = event.target.closest('.star');
                if (clickedStar) {
                    if(container.querySelector(".ratingValue").value == parseInt(clickedStar.dataset.value)){
                        container.querySelector(".ratingValue").value = 0;
                    }else{
                        container.querySelector(".ratingValue").value = parseInt(clickedStar.dataset.value);
                    }
                    updateStars(container);
                }
            });
            container.addEventListener("mouseover",(event)=>{
                var hoveredStar = event.target.closest('.star');
                if (hoveredStar){
                    var stars = container.querySelectorAll('.star-rating .star');
                    stars.forEach(element => {
                        if(element.dataset.value <= hoveredStar.dataset.value){
                            element.classList.add('selected');
                        } else {
                            element.classList.remove('selected');
                        }
                    });
                }
            });
            container.addEventListener("mouseout",(event)=>{
                updateStars(container);
            });
        });

</script>

<script>
    var eventFilter = -1;
    var positionFilter = -1;
    var nameFilter = "";
    var valFilter = false;
    var starFilter = 0;

    function updateFilter(){
        const events = document.querySelectorAll(".evento-lista-stu");
        const studenti = document.querySelectorAll("tr.studente");

        if (parseInt(eventFilter) == -1){
            events.forEach(element => {
                element.style.display = "block";
            });
        }
        else{
            events.forEach(element => {
                element.style.display = "none";
            });
            events[eventFilter].style.display = "block";
        }

        studenti.forEach(element => {
            var cond1 = (element.dataset.pos == positionFilter || positionFilter == -1);
            var cond2 = element.innerHTML.toLowerCase().includes(nameFilter.toLowerCase());
            var cond3 =  !valFilter || (valFilter && element.querySelector("input[type='checkbox']").checked);
            var cond4 = parseInt(element.dataset.val) >= parseInt(starFilter);
            if(cond1&&cond2&&cond3&&cond4){
                element.style.display = "table-row";
            }
            else{
                element.style.display = "none";
            }
        });
        document.querySelector(".overview").classList.remove("expanded");
        document.querySelector(".overview .expanded").classList.remove("expanded");
    }

    const select = document.querySelector("#selectEvent");
    select.addEventListener("change", (event)=>{
        console.log(event);
        console.log(select.value);
        eventFilter = select.value;
        updateFilter();
    });
    const selectPos = document.querySelector("#selectPosition");
    selectPos.addEventListener("change", (event)=>{
        positionFilter = selectPos.value;
        updateFilter();
    });
    const search = document.querySelector("#searchBar");
    search.addEventListener("keyup",(event)=>{
        const studenti = document.querySelectorAll(".stu-name");
        nameFilter = search.value; 
        updateFilter();
    });
    const checkbox = document.querySelector("#onlyCompleted");
    checkbox.addEventListener("change",(event)=>{
        valFilter = checkbox.checked;
        updateFilter();
    });
    const starsFilterInputs = document.querySelectorAll(".startFilterBt input");
    starsFilterInputs.forEach(element => {
        element.addEventListener("change", event=>{
            starFilter = element.dataset.value;
            updateFilter();
        });
    });

</script>