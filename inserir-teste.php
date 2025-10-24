<?php
// Inclui o arquivo de conexão que já está funcionando
require_once 'conexao.php';

echo "<h1>Inserindo vídeos de teste...</h1>";

// Limpa a tabela para não inserir dados repetidos toda vez que o script for executado
$pdo->exec('DELETE FROM videos;'); 
    
// Prepara o comando SQL para inserir os vídeos
$sql = 'INSERT INTO videos (url, title) VALUES (?, ?);';
$statement = $pdo->prepare($sql);
    
// --- Vídeo 1 ---
$statement->bindValue(1, 'https://www.youtube.com/embed/FAY1K2aUg5g');
$statement->bindValue(2, 'Conhecendo a linguagem Go | Hipsters.Talks');
$statement->execute();
echo "<p>Vídeo 'Conhecendo Go' inserido com sucesso!</p>";

// --- Vídeo 2 ---
$statement->bindValue(1, 'https://www.youtube.com/embed/pA-EgOaF23I');
$statement->bindValue(2, 'Qual é o melhor hardware para programação com Mario Souto');
$statement->execute();
echo "<p>Vídeo 'Melhor Hardware' inserido com sucesso!</p>";

// --- Vídeo 3 ---
$statement->bindValue(1, 'https://www.youtube.com/embed/YhnNOTde2I0');
$statement->bindValue(2, 'Mercado de Trabalho | Desmistificando Mobile');
$statement->execute();
echo "<p>Vídeo 'Desmistificando Mobile' inserido com sucesso!</p>";

// --- VÍDEO NOVO ADICIONADO AQUI ---
$statement->bindValue(1, 'https://www.youtube.com/embed/tBweoUi-GCR');
$statement->bindValue(2, 'O que o PHP é capaz de fazer? | Hipsters Ponto Tech');
$statement->execute();
echo "<p>Vídeo 'O que o PHP é capaz de fazer' inserido com sucesso!</p>";


echo "<h2><a href='index.php'>Voltar para a página principal</a></h2>";

?>