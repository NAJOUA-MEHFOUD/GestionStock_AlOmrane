<?php
require_once 'database.php';

$searchType = $_POST['searchType'];
$searchValue = $_POST['searchValue'];

// Construction de la requête préparée en fonction du type de recherche
if ($searchType === "Matricule") {
    $query = "SELECT * FROM affectation WHERE Matricule = :searchValue";
} elseif ($searchType === "Inventaire") {
    $query = "SELECT * FROM affectation WHERE Inventaire = :searchValue";
} elseif ($searchType === "Data_aff") {
    $query = "SELECT * FROM materiel WHERE Data_aff = :searchValue";
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