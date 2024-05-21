<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$Matricule = $Inventaire = $Data_aff = "";
$matricule_err = $inventaire_err = $data_aff_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get values from the hidden inputs
    $matricule = $_POST["Matricule"];
    $inventaire = $_POST["Inventaire"];

    // Validate Matricule
    $input_matricule = trim($_POST["Matricule"]);
    if (empty($input_matricule)) {
        $matricule_err = "Veuillez entrer un matricule.";
    } elseif (!filter_var(trim($_POST["Matricule"]), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s ]+$/")))) {
        $matricule_err = 'Veuillez entrer un matricule valide.';
    } else {
        $Matricule = $input_matricule;
    }

    // Validate Inventaire
    $input_inventaire = trim($_POST["Inventaire"]);
    if (empty($input_inventaire)) {
        $inventaire_err = 'Veuillez entrer un numéro d\'inventaire.';
    } else {
        $Inventaire = $input_inventaire;
    }

    // Validate Data_aff
    $input_data_aff = trim($_POST["Data_aff"]);
    if (empty($input_data_aff)) {
        $data_aff_err = "Veuillez entrer une date d'affectation.";
    } elseif (!strtotime($input_data_aff)) {
        $data_aff_err = 'Veuillez entrer une date valide.';
    } else {
        $Data_aff = $input_data_aff;
    }

    // Check input errors before updating in the database
    if (empty($matricule_err) && empty($inventaire_err) && empty($data_aff_err)) {
        // Prepare an update statement
        $sql = "UPDATE affectation SET Matricule=?, Inventaire=?, Data_aff=? WHERE Matricule=? AND Inventaire=?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("siisi", $param_matricule, $param_inventaire, $param_data_aff, $param_old_matricule, $param_old_inventaire);

            // Set parameters
            $param_matricule = $Matricule;
            $param_inventaire = $Inventaire;
            $param_data_aff = $Data_aff;
            $param_old_matricule = $matricule;
            $param_old_inventaire = $inventaire;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records updated successfully. Redirect to the landing page
                header("location: Lister_affectation.php");
                exit();
            } else {
                echo "Quelque chose s'est mal passé. Veuillez réessayer ultérieurement.";
            }
        }
        // Close the statement
        $stmt->close();
    }
} else {
    // Check existence of Matricule and Inventaire parameters in the URL before processing further
    if (isset($_GET["Matricule"]) && isset($_GET["Inventaire"])) {
        // Get URL parameters
        $matricule = $_GET["Matricule"];
        $inventaire = $_GET["Inventaire"];

        // Prepare a select statement
        $sql = "SELECT * FROM affectation WHERE Matricule = ? AND Inventaire = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_matricule, $param_inventaire);

            // Set parameters
            $param_matricule = $matricule;
            $param_inventaire = $inventaire;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use a while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $Matricule = $row["Matricule"];
                    $Inventaire = $row["Inventaire"];
                    $Data_aff = $row["Data_aff"];
                } else {
                    // URL doesn't contain valid parameters. Redirect to the error page
                    header("location: Lister_affectation.php");
                    exit();
                }
            } else {
                echo "Quelque chose s'est mal passé. Veuillez réessayer ultérieurement.";
            }
        }
        // Close the statement
        $stmt->close();
    } else {
        // URL doesn't contain the necessary parameters. Redirect to the error page
        header("location: Lister_affectation.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Mettre à jour l'affectation</h2>
                    </div>
                    <p>Veuillez modifier les valeurs d'entrée et soumettre pour mettre à jour l'affectation.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($matricule_err)) ? 'has-error' : ''; ?>">
                            <label>Matricule</label>
                            <input type="text" name="Matricule" class="form-control" value="<?php echo $Matricule; ?>">
                            <span class="help-block"><?php echo $matricule_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($inventaire_err)) ? 'has-error' : ''; ?>">
                            <label>Inventaire</label>
                            <input type="text" name="Inventaire" class="form-control" value="<?php echo $Inventaire; ?>">
                            <span class="help-block"><?php echo $inventaire_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($data_aff_err)) ? 'has-error' : ''; ?>">
                            <label>Date d'affectation</label>
                            <input type="date" name="Data_aff" class="form-control" value="<?php echo $Data_aff; ?>">
                            <span class="help-block"><?php echo $data_aff_err; ?></span>
                        </div>
                        <input type="hidden" name="Matricule" value="<?php echo $matricule; ?>"/>
                        <input type="hidden" name="Inventaire" value="<?php echo $inventaire; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Soumettre">
                        <a href="Lister_affectation.php" class="btn btn-default">Annuler</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
