<?php
// Récupérer la clé primaire envoyée depuis la requête AJAX
$primaryKey = $_POST['primaryKey'];

// Effectuer la suppression dans la base de données
require_once 'database.php';
$sql = "DELETE FROM ticket WHERE Id_ticket = :primaryKey";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':primaryKey', $primaryKey);
$stmt->execute();

// Générer une réponse JSON pour indiquer le succès ou l'échec de la suppression
$response = array('success' => true);
echo json_encode($response);
?>