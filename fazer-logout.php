<?php
session_start();

session_unset();
session_destroy();

// MANDA DE VOLTA PARA O NOVO login.php
header("Location: /login.php");
exit();

