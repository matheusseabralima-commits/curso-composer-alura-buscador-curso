<?php

$dbPath = __DIR__ . "/../banco.sqlite";

$pdo = new PDO("sqlite:$dbPath");

$pdo->exec('CREATE TABLE IF NOT EXISTS videos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    url TEXT NOT NULL,
    title TEXT NOT NULL
);');
