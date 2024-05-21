<?php
// search_ticket.php

// Vérifier si la requête est envoyée en utilisant la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer le type de recherche et la valeur de recherche
    $searchType = $_POST["searchType"];
    $searchValue = $_POST["searchValue"];

    // Validation minimale (vous pouvez ajouter des validations supplémentaires si nécessaire)
    if (isset($searchType) && isset($searchValue)) {
        // Effectuer la recherche dans la base de données en fonction du type et de la valeur de recherche
        try {
            require_once 'database.php';

            $sql = "SELECT * FROM ticket WHERE $searchType LIKE :searchValue";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':searchValue', "%$searchValue%", PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Renvoyer les résultats de recherche au format JSON
            echo json_encode($results);
        } catch (PDOException $e) {
            // En cas d'erreur, renvoyer une réponse JSON avec un message d'erreur
            echo json_encode(array("error" => "Erreur lors de la recherche dans la base de données."));
        }
    } else {
        // En cas de paramètres manquants, renvoyer une réponse JSON avec un message d'erreur
        echo json_encode(array("error" => "Paramètres de recherche manquants."));
    }
} else {
    // Si la requête n'est pas envoyée en utilisant la méthode POST, renvoyer une réponse JSON avec un message d'erreur
    echo json_encode(array("error" => "Méthode non autorisée."));
}
?>