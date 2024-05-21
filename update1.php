<?php
require_once 'database.php';

// Récupérer les données envoyées depuis la requête AJAX
$primaryKey = $_POST['primaryKey'];

$column = $_POST['column'];
$value = $_POST['value'];

// Effectuer la mise à jour dans la base de données
$sql = "UPDATE fournisseur SET $column = :columnValue WHERE id_fiscale = :primaryKey";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':columnValue', $value);
$stmt->bindParam(':primaryKey', $primaryKey);
$stmt->execute();

// Générer une réponse JSON pour indiquer le succès ou l'échec de la mise à jour
$response = array('success' => true);
echo json_encode($response);
?>
