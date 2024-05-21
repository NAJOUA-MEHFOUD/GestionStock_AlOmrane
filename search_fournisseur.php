<?php
require_once 'database.php';

$searchType = $_POST['searchType'];
$searchValue = $_POST['searchValue'];

// Construction de la requête préparée en fonction du type de recherche
if ($searchType === "id_fiscale") {
    $query = "SELECT * FROM fournisseur WHERE id_fiscale = :searchValue";
} elseif ($searchType === "Nom") {
    $query = "SELECT * FROM fournisseurr WHERE Nom = :searchValue";
} elseif ($searchType === "Prenom") {
    $query = "SELECT * FROM fournisseur WHERE Prenom = :searchValue";
} elseif ($searchType === "Numero_téléphone") {
    $query = "SELECT * FROM fournisseur WHERE Numero_téléphone = :searchValue";
} elseif ($searchType === "Adresse_mail") {
    $query = "SELECT * FROM fournisseur WHERE Adresse_mail = :searchValue";
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