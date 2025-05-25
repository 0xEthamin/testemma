<?php
class Bien {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function ajouterBien($titre, $description, $prix, $adresse, $imagePath = null) {
        $sql = "INSERT INTO biens (titre, description, prix, adresse, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$titre, $description, $prix, $adresse, $imagePath]);
    }
}
