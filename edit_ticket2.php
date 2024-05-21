<?php
// Include config file
require_once 'config.php';
 
// Define variables and initialize with empty values
$Titre = $Priorite = $Categorie = $Date = $Status = $Description = "";
$titre_err = $priorite_err = $categorie_err = $date_err = $status_err = $description_err ="";
 
// Processing form data when form is submitted
if (isset($_POST["Id_ticket"]) && !empty($_POST["Id_ticket"])) {
    // Get hidden input value
    $id = $_POST["Id_ticket"];
    
    // Validate titre.
$input_titre = trim($_POST["Titre"]);
if (empty($input_titre)) {
    $titre_err = "Please enter a titre.";
} elseif (!preg_match("/^[a-zA-Z0-9'-.\s]+$/", $input_titre)) {
    $titre_err = 'Please enter a valid titre.';
} else {
    $Titre = $input_titre;
}



        
    // Validate priorite
    $input_priorite = trim($_POST["Priorite"]);
    if (empty($input_priorite)) {
        $priorite_err = 'Please enter a priorite.';
    } elseif (!filter_var(trim($_POST["Priorite"]), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s ]+$/")))) {
        $priorite_err = 'Please enter a valid Prenom.';
    } else {
        $Priorite = $input_priorite;
    }


   // Validate categorie
$input_categorie = trim($_POST["Categorie"]);
if (empty($input_categorie)) {
    $categorie_err = 'Please select a categorie.';
} elseif (!in_array($input_categorie, ["Matériel", "logiciel", "Réseau", "demande d'accès"])) {
    $categorie_err = 'Please select a valid categorie.';
} else {
    $Categorie = $input_categorie;
}

    
    // Validate date
    $input_date = trim($_POST["Date"]);
    if (empty($input_date)) {
        $date_err = "Please enter the phone number.";
    } elseif(!strtotime($input_date)){
        $date_err = 'Please enter a valid phone number.';    
    } else {
        $Date = $input_date;
    }

   // Validate Status
$input_status = trim($_POST["Status"]);
if (empty($input_status)) {
    $status_err = "Please enter the etat.";
} elseif(!filter_var(trim($_POST["Status"]), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z'-.\s ]+$/")))){
    $status_err = 'Please enter a valid etat.';
} else {
    $Status = $input_status;
}

    // Validate Description
$input_description = trim($_POST["Description"]);
if (empty($input_description)) {
    $description_err = "Please enter the Description.";
} else {
    $Description = $input_description;
}



    // Check input errors before inserting in database
    if (empty($titre_err) && empty($priorite_err) && empty($categorie_err) && empty($date_err) && empty($status_err) && empty($description_err)) {
        // Prepare an insert statement
        $sql = "UPDATE ticket SET Titre=?, Priorité=?, Categorie=?, Date_creation=?, status=?, Description=? WHERE Id_ticket=?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssi", $param_titre, $param_priorite, $param_categorie, $param_date, $param_status, $param_descrition, $param_id);
            
            // Set parameters
            $param_titre = $Titre;
            $param_priorite = $Priorite;
            $param_categorie= $Categorie;
            $param_date = $Date;
            $param_status = $Status;
            $param_descrition= $Description;
            $param_id=$id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records updated successfully. Redirect to landing page
                header("location: Lister_ticket.php");
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
        $sql = "SELECT * FROM ticket WHERE Id_ticket = ?";
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
                    $Titre = $row["Titre"];
                    $Priorite = $row["Priorité"];
                    $Categorie = $row["Categorie"];
                    $Date = $row["Date_creation"];
                    $Status = $row["Status"];
                    $Description = $row["Description"];

                    
                } else {
                    // URL doesn't contain a valid id. Redirect to the error page
                    header("location: Lister_ticket.php");
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
        header("location: Lister_ticket.php");
        exit();
    }
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>edit ticket</title>
    <style>
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
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
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
                    <div class="page-header"></div>
                    <p id="oo">Veuillez modifier les valeurs d'entrée et soumettre pour mettre à jour l'enregistrement</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="grid-container">
                            <label for="titre">Titre:</label>
                            <input type="text" name="Titre" id="titre" class="form-control" value="<?php echo $Titre; ?>">
                        </div><br>

                        <div class="grid-container">
                            <label for="priorite">Priorité:</label>
                            <input type="text" name="Priorite" id="priorite" class="form-control" value="<?php echo $Priorite; ?>">
                        </div><br>

                        <div class="grid-container">
                            <label for="d3">Catégorie:</label>
                            <select id="d3" name="Categorie" class="form-control">
                                <option value="Matériel"<?php if ($Categorie === 'Matériel') echo ' selected'; ?>>Matériel</option>
                                <option value="logiciel"<?php if ($Categorie === 'logiciel') echo ' selected'; ?>>logiciel</option>
                                <option value="Réseau"<?php if ($Categorie === 'Réseau') echo ' selected'; ?>>Réseau</option>
                                <option value="demande d'accès"<?php if ($Categorie === 'demande d\'accès') echo ' selected'; ?>>demande d'accès</option>
                            </select>
                        </div><br>

                        <div class="grid-container">
                            <label for="date">Date création:</label>
                            <input type="text" name="Date" id="date" class="form-control" value="<?php echo $Date; ?>">
                        </div><br>

                        <div class="grid-container">
                            <label for="status">Statuts:</label>
                            <input type="text" name="Status" id="status" class="form-control" value="<?php echo $Status; ?>">
                        </div><br>

                        <div class="grid-container">
                            <label for="description">Description:</label>
                            <input type="text" name="Description" id="description" class="form-control" value="<?php echo $Description; ?>">
                        </div>
                        <br><br>
                        <!-- Fin du formulaire -->
                        <input type="hidden" name="Id_ticket" value="<?php echo $id; ?>"/>
                        <div class="btn-container">
                            <button type="submit" class="btn btn-primary" id="ss">Soumettre</button>
                            <a href="Lister_ticket.php" class="btn btn-primary" id="hh">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>