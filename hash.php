<?php
$password = "admin"; // Remplace par ton mot de passe réel
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Mot de passe hashé : " . $hash;
?>