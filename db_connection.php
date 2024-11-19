<?php
try {
    // Créer ou ouvrir la base de données SQLite
    $db = new PDO('sqlite:gambling_db.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la table `user` si elle n'existe pas déjà
    $db->exec("CREATE TABLE IF NOT EXISTS user (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        money REAL DEFAULT 0.0
    )");
} catch (PDOException $e) {
    echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
    exit;
}
?>