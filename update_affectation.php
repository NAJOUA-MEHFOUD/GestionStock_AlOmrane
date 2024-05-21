<?php
// update_affectation.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once 'database.php';

    // Récupérer les valeurs de matricule et d'inventaire depuis la requête AJAX
    $matricule = $_POST['matricule'];
    $inventaire = $_POST['inventaire'];

    // Construire la requête SQL pour mettre à jour la date de fin d'affectation et l'état du matériel
    $sql = "UPDATE affectation SET Date_fin = NOW() WHERE Matricule = :matricule AND Inventaire = :inventaire";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
    $stmt->bindParam(':inventaire', $inventaire, PDO::PARAM_STR);

    // Exécuter la requête de mise à jour
    $success = $stmt->execute();

    // Mise à jour de l'état du matériel
    if ($success) {
        $sql_update_materiel = "UPDATE materiel SET Etat = 'libre' WHERE Inventaire = :inventaire";
        $stmt_update_materiel = $pdo->prepare($sql_update_materiel);
        $stmt_update_materiel->bindParam(':inventaire', $inventaire, PDO::PARAM_STR);
        $stmt_update_materiel->execute();
        
        // Renvoyer une réponse JSON indiquant le succès de la mise à jour
        if ($stmt_update_materiel->rowCount() > 0) {
            // Récupérez la nouvelle date de fin et envoyez-la en réponse
            $newDateFin = date("Y-m-d "); // Obtenez la date actuelle
            echo json_encode(['success' => true, 'newDateFin' => $newDateFin]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
}


?>