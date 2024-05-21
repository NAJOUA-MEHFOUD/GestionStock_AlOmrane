<?php
$id = $Titre = $Priorité = $Categorie = $Date_creation = $Status = $Description = "";
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    require_once 'config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $Titre = $_POST["Titre"];
        $Priorité = $_POST["Priorité"];
        $Categorie = $_POST["Categorie"];
        $Date_creation = $_POST["Date_creation"];
        $Status = $_POST["Status"];
        $Description = $_POST["Description"];

        // Valider les données du formulaire ici (si nécessaire)

        // Si les données sont valides, effectuez la mise à jour
        $sql = "UPDATE ticket SET Titre=?, Priorité=?, Categorie=?, Date_creation=? , Status=?, Description=? WHERE Id_ticket=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssssssi", $Titre, $Priorité, $Categorie, $Date_creation, $Status, $Description, $id);

        if ($stmt->execute()) {
            // Rediriger vers la liste des matériels après la mise à jour
            header("location: Lister_ticket.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour de l'enregistrement.";
        }
        $stmt->close();
    } else {
        $sql = "SELECT * FROM ticket WHERE Id_ticket= ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $Titre = $row["Titre"];
                $Marque = $row["Priorité"];
                $Categorie = $row["Categorie"];
                $Date_creation = $row["Date_creation"];
                $Status = $row["Status"];
                $Description = $row["Description"];
            } else {
                echo "Aucun enregistrement trouvé avec cet ID.";
                exit();
            }
        } else {
            echo "Erreur lors de l'exécution de la requête.";
            exit();
        }
        $stmt->close();
    }
    $mysqli->close();
} else {
    echo "ID non fourni dans l'URL.";
    exit();
}
?>

<?php
// ... (Le code PHP existant reste inchangé)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le ticket</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        /* Le style que vous avez donné */
        body {
            background-image: url(update.jpg);
            background-size: cover;
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrapper {
            width: 500px;
            margin: 0 auto;
            overflow-y: auto; /* Ajoutez cette ligne pour activer la barre de défilement si nécessaire */
        }

        .container-fluid {
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 5px;
        }

        .form-group {
            display: flex; /* Disposer les éléments du formulaire côte à côte */
            align-items: center; /* Centrer les éléments verticalement */
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            font-size: 14px; /* Taille de police réduite pour les libellés */
            flex: 1; /* Laisser le label occuper un tiers de la largeur */
        }

        .form-group input,
        .form-group textarea {
            flex: 2; /* Laisser les champs de saisie occuper les deux tiers de la largeur */
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-group .help-block {
            color: red;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn-container .btn {
            margin-right: 10px;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            background-color: blue;
            color: white;
            cursor: pointer;
        }

        #oo {
            font-size: 20px;
            font-style: italic;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                    </div>
                    <p id="oo">Veuillez modifier les valeurs d'entrée et soumettre pour mettre à jour l'enregistrement</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Titre :</label>
                            <input type="text" name="Titre" class="form-control" value="<?php echo $Titre; ?>">
                        </div>

                        <div class="form-group">
                            <label>Priorité :</label>
                            <input type="text" name="Priorité" class="form-control" value="<?php echo $Priorité; ?>">
                        </div>

                        <div class="form-group">
                            <label>Categorie :</label>
                            <input type="text" name="Categorie" class="form-control" value="<?php echo $Categorie; ?>">
                        </div>

                        <div class="form-group">
                            <label>Date_creation :</label>
                            <input type="text" name="Date_creation" class="form-control" value="<?php echo $Date_creation; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Status :</label>
                            <input type="text" name="Status" class="form-control" value="<?php echo $Status; ?>">
                        </div>

                        <div class="form-group">
                            <label>Description :</label>
                            <input type="text" name="Description" class="form-control" value="<?php echo $Description; ?>">
                        </div>

                        <div class="btn-container">
                            <button type="submit" class="btn btn-primary">Soumettre</button>
                            <a href="Lister_ticket.php" class="btn btn-primary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>