<?php
session_start();
session_destroy();
// rindirizzamento alla pagina index.html
header('Location: index.html');
?>