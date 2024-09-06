<?php

$date = date('d/m/y');

echo <<<HTML
    <footer class=footer>
        <p class="footer-text">Acessado por {$_SERVER["REMOTE_ADDR"]} em $date</p>
        <p class="footer-text">Desenvolvido por Luis Eduardo</p>
    </footer>
HTML;

?>