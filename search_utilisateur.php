<?php
require_once 'database.php';

$searchType = $_POST['searchType'];
$searchValue = $_POST['searchValue'];

// Construction de la requête préparée en fonction du type de recherche
if ($searchType === "Matricule") {
    $query = "SELECT * FROM utilisateur WHERE Matricule = :searchValue";
} elseif ($searchType === "Nom") {
    $query = "SELECT * FROM utilisateur WHERE Nom = :searchValue";
} elseif ($searchType === "Prénom") {
    $query = "SELECT * FROM utilisateur WHERE Prénom = :searchValue";
} elseif ($searchType === "Numero_téléphone") {
    $query = "SELECT * FROM utilisateur WHERE Numero_téléphone = :searchValue";
} elseif ($searchType === "Login") {
    $query = "SELECT * FROM utilisateur WHERE Login = :searchValue";
} elseif ($searchType === "Direction") {
    $query = "SELECT * FROM utilisateur WHERE Direction = :searchValue";
}elseif ($searchType === "Type") {
    $query = "SELECT * FROM utilisateur WHERE type = :searchValue";
}
// Exécution de la requête préparée
$stmt = $pdo->prepare($query);
$stmt->bindParam(':searchValue', $searchValue);
$stmt->execute();

// Récupération des résultats
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Conversion des résultats en JSON
$jsonResults = json_encode($results);

// Envoi des résultats en tant que réponse JSON
header('Content-Type: application/json');
echo $jsonResults;
?>