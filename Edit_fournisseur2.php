<?php
// Include config file
require_once 'config.php';
 
// Define variables and initialize with empty values
$Nom = $Prenom = $Numero_téléphone = $Adresse_mail = "";
$nom_err = $prenom_err = $numero_err = $mail_err = "";
 
// Processing form data when form is submitted
if (isset($_POST["id_fiscale"]) && !empty($_POST["id_fiscale"])) {
    // Get hidden input value
    $id = $_POST["id_fiscale"];
    
    // Validate nom
    $input_nom = trim($_POST["Nom"]);
    if (empty($input_nom)) {
        $nom_err = "Veuillez entrer un nom";
    } elseif (!filter_var(trim($_POST["Nom"]), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s ]+$/")))) {
        $nom_err = 'Veuillez entrer un nom';
    } else {
        $Nom = $input_nom;
    }
    
    // Validate Prenom
    $input_prenom = trim($_POST["Prenom"]);
    if (empty($input_prenom)) {
        $prenom_err = 'Veuillez entrer un prénom.';
    } elseif (!filter_var(trim($_POST["Prenom"]), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s ]+$/")))) {
        $prenom_err = 'Veuillez entrer un prénom.';
    } else {
        $Prenom = $input_prenom;
    }
    
    // Validate numero
    $input_numero = trim($_POST["Numero_téléphone"]);
    if (empty($input_numero)) {
        $numero_err = "Veuillez entrer un numero de téléphone.";
    } elseif(!preg_match("/^[0-9\s()-]+$/", $input_numero)){
        $numero_err = 'Veuillez entrer un numero de téléphone.';    
    } else {
        $Numero_téléphone = $input_numero;
    }

    // Validate adresse
    $input_mail = trim($_POST["Adresse_mail"]);
    if (empty($input_mail)) {
        $mail_err = "Veuillez entrer une adresse mail.";
    } elseif(!filter_var($input_mail, FILTER_VALIDATE_EMAIL)){
        $mail_err = 'Veuillez entrer une adresse mail.';
    } else {
        $Adresse_mail = $input_mail;
    }

    // Check input errors before inserting in database
    if (empty($nom_err) && empty($prenom_err) && empty($numero_err) && empty($mail_err)) {
        // Prepare an insert statement
        $sql = "UPDATE fournisseur SET Nom=?, Prenom=?, Numero_téléphone=?, Adresse_mail=? WHERE id_fiscale=?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssi", $param_nom, $param_prenom, $param_numero, $param_mail, $param_id);
            
            // Set parameters
            $param_nom = $Nom;
            $param_prenom = $Prenom;
            $param_numero = $Numero_téléphone;
            $param_mail = $Adresse_mail;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records updated successfully. Redirect to landing page
                header("location: Lister_fournisseur2.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM fournisseur WHERE id_fiscale = ?";
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
                    $Prenom = $row["Prenom"];
                    $Numero_téléphone = $row["Numero_téléphone"];
                    $Adresse_mail = $row["Adresse_mail"];
                } else {
                    // URL doesn't contain a valid id. Redirect to the error page
                    header("location: Lister_fournisseur2.php");
                    exit();
                }
                
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close the statement
        $stmt->close();
        
        // Close the connection
        $mysqli->close();
    } else {
        // URL doesn't contain an id parameter. Redirect to the error page
        header("location: Lister_fournisseur2.php");
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
            color: black; /* Set text color to black */
            padding: 20px;
            border-radius: 5px;
            position: relative; /* Add this line to position the buttons inside */
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
            display: inline-block; /* Add this line to put the buttons on the same line */
            margin: 10px; /* Add some margin around the buttons */
        }

        #oo {
            font-size: 20px;
            font-style: italic;
            text-align: center;
        }
        .white-bg-form {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
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
                   
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" class="white-bg-form">
                    <p id="oo">Veuillez modifier les valeurs d'entrée et soumettre pour mettre à jour l'enregistrement.</p>
                        <div class="form-container"> <!-- Add the form-container class here -->
                            <div class="form-group <?php echo (!empty($nom_err)) ? 'has-error' : ''; ?>">
                                <label>Nom</label>
                                <input type="text" name="Nom" class="form-control" value="<?php echo $Nom; ?>">
                                <span class="help-block"><?php echo $nom_err;?></span>
                            </div>
                            <div class="form-group <?php echo (!empty($prenom_err)) ? 'has-error' : ''; ?>">
                                <label>Prénom</label>
                                <input type ="text" name="Prenom" class="form-control" value="<?php echo $Prenom; ?>">
                                <span class="help-block"><?php echo $prenom_err;?></span>
                            </div>

                            <div class="form-group <?php echo (!empty($numero_err)) ? 'has-error' : ''; ?>">
                                <label>Numero de téléphone</label>
                                <textarea name="Numero_téléphone" class="form-control"><?php echo $Numero_téléphone; ?></textarea>
                                <span class="help-block"><?php echo $numero_err;?></span>
                                
                            </div>

                            <div class="form-group <?php echo (!empty($mail_err)) ? 'has-error' : ''; ?>">
                                <label>Adresse email</label>
                                <input type="text" name="Adresse_mail" class="form-control" value="<?php echo $Adresse_mail; ?>">
                                <span class="help-block"><?php echo $mail_err;?></span>
                                
                            </div>

                            <input type="hidden" name="id_fiscale" value="<?php echo $id; ?>"/>
                            <div class="btn-container">
                                <input type="submit" class="btn btn-primary" value="Soumettre" name="Submit">
                                <a href="Lister_fournisseur2.php" class="btn btn-default">Fermer</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>