<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$Nom = $Prenom = $Numero_téléphone = $Adresse_mail = $Direction = $Type = "";
$nom_err = $prenom_err = $numero_err = $mail_err = $direction_err = $type_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Submit"])) {
    // Get hidden input value
    if (isset($_POST["Matricule"]) && !empty($_POST["Matricule"])) {
        $id = $_POST["Matricule"];

        // Validate nom
        if (isset($_POST["Nom"]) && !empty($_POST["Nom"])) {
            $input_nom = trim($_POST["Nom"]);
            if (!filter_var($input_nom, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s ]+$/")))) {
                $nom_err = 'Veuillez entrer un nom valide.';
            } else {
                $Nom = $input_nom;
            }
        } else {
            $nom_err = "Veuillez entrer un nom.";
        }

        // Validate Prenom
        if (isset($_POST["Prénom"]) && !empty($_POST["Prénom"])) {
            $input_prenom = trim($_POST["Prénom"]);
            if (!filter_var($input_prenom, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s ]+$/")))) {
                $prenom_err = 'Veuillez entrer un prénom valide.';
            } else {
                $Prenom = $input_prenom;
            }
        } else {
            $prenom_err = 'Veuillez entrer un prénom.';
        }

        // Validate numero
        if (isset($_POST["Numero_téléphone"]) && !empty($_POST["Numero_téléphone"])) {
            $input_numero = trim($_POST["Numero_téléphone"]);
            if (!preg_match("/^[0-9\s()-]+$/", $input_numero)) {
                $numero_err = 'Veuillez entrer un numéro de téléphone valide.';
            } else {
                $Numero_téléphone = $input_numero;
            }
        } else {
            $numero_err = "Veuillez entrer un numéro de téléphone.";
        }

        // Validate login
        if (isset($_POST["Login"]) && !empty($_POST["Login"])) {
            $input_mail = trim($_POST["Login"]);
            if (!filter_var($input_mail, FILTER_VALIDATE_EMAIL)) {
                $mail_err = 'Veuillez entrer une adresse mail valide.';
            } else {
                $Adresse_mail = $input_mail;
            }
        } else {
            $mail_err = "Veuillez entrer une adresse mail.";
        }

        // Validate Direction
        if (isset($_POST["Direction"]) && !empty($_POST["Direction"])) {
            $input_direction = trim($_POST["Direction"]);
            if (!filter_var($input_direction, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s ]+$/")))) {
                $direction_err = 'Veuillez entrer une direction valide.';
            } else {
                $Direction = $input_direction;
            }
        } else {
            $direction_err = "Veuillez entrer une direction.";
        }

        // Validate Type
        if (isset($_POST["type"]) && !empty($_POST["type"])) {
            $input_type = trim($_POST["type"]);
            if (!filter_var($input_type, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s ]+$/")))) {
                $type_err = 'Veuillez entrer un type valide.';
            } else {
                $Type = $input_type;
            }
        } else {
            $type_err = "Veuillez entrer un type.";
        }

        // Check input errors before inserting in database
        if (empty($nom_err) && empty($prenom_err) && empty($numero_err) && empty($mail_err) && empty($direction_err) && empty($type_err)) {
            // Prepare an insert statement
            $sql = "UPDATE utilisateur SET Nom=?, Prénom=?, Numero_téléphone=?, Login=?, Direction=?, type=? WHERE Matricule=?";

            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ssssssi", $param_nom, $param_prenom, $param_numero, $param_mail, $param_direction, $param_type, $param_id);

                // Set parameters
                $param_nom = $Nom;
                $param_prenom = $Prenom;
                $param_numero = $Numero_téléphone;
                $param_mail = $Adresse_mail;
                $param_direction = $Direction;
                $param_type = $Type;
                $param_id = $id;

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Records updated successfully. Redirect to landing page
                    header("location: Lister_utilisateur.php");
                    exit();
                } else {
                    echo "Quelque chose s'est mal passé. Veuillez réessayer ultérieurement.";
                }
            }
        }
    } else {
        // Redirection en cas de problème avec l'id
        header("location: Lister_utilisateur.php");
        exit();
    }
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM utilisateur WHERE Matricule = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use a while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $Nom = $row["Nom"];
                    $Prenom = $row["Prénom"];
                    $Numero_téléphone = $row["Numero_téléphone"];
                    $Adresse_mail = $row["Login"];
                    $Direction = $row["Direction"];
                    $Type = $row["type"];
                } else {
                    // L'URL ne contient pas d'identifiant valide. Redirige vers la page d'erreur
                    header("location: Lister_utilisateur.php");
                    exit();
                }
            } else {
                echo "Oops! Quelque chose s'est mal passé. Veuillez réessayer ultérieurement.";
            }
        }

        // Ferme la déclaration préparée
        $stmt->close();

        // Ferme la connexion
        $mysqli->close();
    } else {
        // L'URL ne contient pas d'identifiant. Redirige vers la page d'erreur
        header("location: Lister_utilisateur.php");
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
            overflow-y: auto; /* Add this line to enable the scrollbar if necessary */
        }

        .form-container {
            background-color: white; /* Set the background color of the form to white */
            padding: 20px;
            border-radius: 5px;
        }

        .form-group {
            display: flex; /* Arrange form elements side by side */
            align-items: center; /* Center elements vertically */
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            font-size: 14px; /* Reduced font size for labels */
            flex: 1; /* Let the label occupy one-third of the width */
        }

        .form-group input,
        .form-group textarea {
            flex: 2; /* Let the input fields occupy two-thirds of the width */
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
        <div class="form-container">
            <p id="oo">Veuillez modifier les valeurs d'entrée et soumettre pour mettre à jour l'enregistrement.</p>
            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                <div class="form-group <?php echo (!empty($nom_err)) ? 'has-error' : ''; ?>">
                    <label>Nom:</label>
                    <input type="text" name="Nom" class="form-control" value="<?php echo $Nom; ?>">
                    <span class="help-block"><?php echo $nom_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($prenom_err)) ? 'has-error' : ''; ?>">
                    <label>Prénom:</label>
                    <input type="text" name="Prénom" class="form-control" value="<?php echo $Prenom; ?>">
                    <span class="help-block"><?php echo $prenom_err; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($numero_err)) ? 'has-error' : ''; ?>">
                    <label>Numero de téléphone:</label>
                    <input type="text" name="Numero_téléphone" class="form-control" value="<?php echo $Numero_téléphone; ?>">
                    <span class="help-block"><?php echo $numero_err; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($mail_err)) ? 'has-error' : ''; ?>">
                    <label>Adresse email:</label>
                    <input type="text" name="Login" class="form-control" value="<?php echo $Adresse_mail; ?>">
                    <span class="help-block"><?php echo $mail_err; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($direction_err)) ? 'has-error' : ''; ?>">
                    <label>Direction:</label>
                    <input type="text" name="Direction" class="form-control" value="<?php echo $Direction; ?>">
                    <span class="help-block"><?php echo $direction_err; ?></span>
                </div>

                <div class="form-group <?php echo (!empty($type_err)) ? 'has-error' : ''; ?>">
                    <label>Type</label>
                    <input type="text" name="type" class="form-control" value="<?php echo $Type; ?>">
                    <span class="help-block"><?php echo $type_err; ?></span>
                </div>

                <div class="btn-container">
                    <input type="hidden" name="Matricule" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" name="Submit" value="Soumettre">
                    <a href="Lister_utilisateur.php" class="btn btn-default">Fermer</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>