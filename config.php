<?php
$host = 'herogu.garageisep.com';
$dbname = 'LL1QfAKjD6_etulogis';
$user = 'cMHmeHfrqf_etulogis';
$pass = 'JRDCBchpXzuMFBc2';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
