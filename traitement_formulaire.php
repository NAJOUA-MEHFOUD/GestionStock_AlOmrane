<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom_matricule = $_POST['nom_matricule'];
    $nom_inventaire = $_POST['nom_inventaire'];
    $date_affectation = $_POST['date'];

    // Établir la connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=parc_informatique', 'root', '');

    // Vérifier si l'affectation existe déjà dans la table affectation et n'est pas terminée
// Vérifier si l'affectation existe déjà dans la table affectation et n'est pas en cours
$sql_check_affectation = "SELECT * FROM affectation WHERE Matricule = :matricule AND Inventaire = :inventaire AND (Date_fin IS NULL OR Date_fin <= :date_affectation)";
$stmt_check_affectation = $pdo->prepare($sql_check_affectation);
$stmt_check_affectation->execute([
    ':matricule' => $nom_matricule,
    ':inventaire' => $nom_inventaire,
    ':date_affectation' => $date_affectation
]);

   if ($stmt_check_affectation->rowCount() > 0) {
        // L'affectation existe déjà et n'est pas terminée, renvoyer une réponse JSON d'erreur
        $response = array("success" => false, "message" => "Cette affectation est déjà en cours.");
        echo json_encode($response);
    } else {
        // L'affectation n'existe pas ou est terminée, nous pouvons procéder à l'insertion dans la table affectation
        $sql_insert_affectation = "INSERT INTO affectation (Matricule, Inventaire, Data_aff) VALUES (:matricule, :inventaire, :date_affectation)";
        $stmt_insert_affectation = $pdo->prepare($sql_insert_affectation);
        $stmt_insert_affectation->execute([
            ':matricule' => $nom_matricule,
            ':inventaire' => $nom_inventaire,
            ':date_affectation' => $date_affectation
        ]);

        $sql_update_materiel = "UPDATE materiel SET Etat = 'occupé' WHERE Inventaire = :inventaire;";
        $stmt_update_materiel = $pdo->prepare($sql_update_materiel);
        $stmt_update_materiel->execute([
            ':inventaire' => $nom_inventaire,
        ]);

        if ($stmt_insert_affectation->rowCount() > 0) {
            // Enregistrement réussi, renvoyer une réponse JSON de succès
            $response = array("success" => true, "message" => "Enregistrement réussi !");
            echo json_encode($response);
        } else {
            // Erreur lors de l'enregistrement, renvoyer une réponse JSON d'erreur
            $response = array("success" => false, "message" => "Erreur lors de l'enregistrement.");
            echo json_encode($response);
        }
    }
}
?>
