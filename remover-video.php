<?php
require_once 'conexao.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false) {
    header("Location: index.php");
    exit();
}

$sql = "DELETE FROM videos WHERE id = ?;";
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $id, PDO::PARAM_INT);

if ($statement->execute()) {
    header("Location: index.php?sucesso=3");
} else {
    header("Location: index.php?erro=3");
}