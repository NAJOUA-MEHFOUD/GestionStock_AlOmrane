<?php
require_once 'database.php';

$searchType = $_POST['searchType'];
$searchValue = $_POST['searchValue'];

// Construction de la requête préparée en fonction du type de recherche
if ($searchType === "Inventaire") {
    $query = "SELECT * FROM materiel WHERE Inventaire = :searchValue";
} elseif ($searchType === "type") {
    $query = "SELECT * FROM materiel WHERE Type = :searchValue";
} elseif ($searchType === "Marque") {
    $query = "SELECT * FROM materiel WHERE Marque = :searchValue";
} elseif ($searchType === "Date_achat") {
    $query = "SELECT * FROM materiel WHERE Date_achat = :searchValue";
} elseif ($searchType === "Etat") {
    $query = "SELECT * FROM materiel WHERE Etat = :searchValue";
} elseif ($searchType === "id_fiscale") {
    $query = "SELECT * FROM materiel WHERE id_fiscale = :searchValue";
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