<footer>
    <p>Progetto realizzato per finalità didattiche dalla 5BI 2025/2026</p>

<?php if(isset($_SESSION["user"]) && $_SESSION["user-type"]==1) :?>
    <a href="https://forms.gle/gnvmgcyke81DPkPM8">Lascia un feedback alla pagina</a>
<?php elseif(isset($_SESSION["user"]) && $_SESSION["user-type"]==2) :?>
    <a href="https://forms.gle/4gKX5mzdCB1UCSDb9">Lascia un feedback alla pagina</a>
<?php elseif(isset($_SESSION["user"]) && $_SESSION["user-type"]==3) :?>
    <a href="https://forms.gle/eBn1jxkSumZq2zUaA">Lascia un feedback alla pagina</a>
<?php endif; ?>

    <p><a href="index.php?pag=faq">FAQs</a></p>

    <p><a href="/php/politica_privacy.php">Politica Privacy</a></p>
</footer>