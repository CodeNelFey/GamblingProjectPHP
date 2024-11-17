<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addUser($name, $password, $money) {
        try {
            // Vérification si l'utilisateur existe déjà
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM user WHERE name = :name");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $userExists = $stmt->fetchColumn();

            if ($userExists) {
                echo "<p class='notif-msg-bad'>Un utilisateur avec ce nom existe déjà.</p>";
                return;
            }

            // Vérification si le mot de passe est identique au nom d'utilisateur
            if ($name === $password) {
                echo "<p class='notif-msg-bad'>Le mot de passe ne peut pas être identique au nom d'utilisateur.</p>";
                return;
            }

            // Hashage du mot de passe avant de l'enregistrer
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insertion de l'utilisateur dans la base de données
            $stmt = $this->db->prepare("INSERT INTO user (name, password, money) VALUES (:name, :password, :money)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':money', $money);
            $stmt->execute();

            echo "<p class='notif-msg-good'>Votre compte a bien été créé, <a href='login.php'>connectez-vous</a> dès maintenant !</p>";
        } catch (PDOException $e) {
            echo "<p class='notif-msg-bad'>Nous n'avons pas pu créer votre compte pour la raison suivante : " . $e->getMessage() . "</p>";
        }
    }
}
?>