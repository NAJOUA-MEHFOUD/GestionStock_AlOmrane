<?php
// Include config file
require_once 'database.php';
 
// Define variables and initialize with empty values
$Nom = $Prenom = $Numero_téléphone = $Adresse_mail= "";
$name_err = $prenom_err = $numero_err = $address_err = $salary_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id_fiscale"])){
    // Get hidden input value
    $id = $_POST["id_fiscale"];
    
    // Validate name
    $input_name = trim($_POST["Nom"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var(trim($_POST["Nom"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $name_err = 'Please enter a valid name.';
    } else{
        $Nom = $input_name;
    }



    //validate Prenom
    $input_prenom = trim($_POST["Prenom"]);
    if(empty($input_prenom)){
        $prenom_err = "Please enter a name.";
    } elseif(!filter_var(trim($_POST["Prenom"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $prenom_err = 'Please enter a valid name.';
    } else{
        $Prenom = $input_prenom;
    }
    
    // Validate Numero_telephone
    $input_numero = trim($_POST["Numero_téléphone"]);
    if(empty($input_numero)){
        $numero_err = 'Please enter an address.';     
    } else{
        $Numero_téléphone = $input_numero;
    }

     
    // Validate adresse mail
    $input_address = trim($_POST["Adresse_mail"]);
    if(empty($input_address)){
        $address_err = 'Please enter an address.';     
    } else{
        $Adresse_mail = $input_address;
    }
    
   
    // Check input errors before inserting in database
if (empty($name_err) && empty($prenom_err) && empty($numero_err) && empty($address_err)) {
    // Prepare an insert statement
    $sql = "UPDATE fournisseur SET Nom=?, Prenom=?, Numero_téléphone=? , Adresse_mail=? WHERE id_fiscale=?";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(1, $param_name);
        $stmt->bindParam(2, $param_prenom);
        $stmt->bindParam(3, $param_numero);
        $stmt->bindParam(4, $param_adresse);
        $stmt->bindParam(5, $param_id);

        // Set parameters
        $param_name = $Nom;
        $param_prenom = $Prenom;
        $param_numero = $Numero_téléphone;
        $param_adresse = $Adresse_mail;
        $param_id = $id;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records updated successfully. Redirect to landing page
            header("location: index.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }
    }
    
    // Close statement
    $stmt = null;
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Nom</label>
                            <input type="text" name="Nom" class="form-control" value="<?php echo $Nom; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($prenom_err)) ? 'has-error' : ''; ?>">
                            <label>Prenom</label>
                            <input type="text" name="Prenom" class="form-control" value="<?php echo $Prenom; ?>">
                            
                        </div>
                        <div class="form-group <?php echo (!empty($numero_err)) ? 'has-error' : ''; ?>">
                            <label>Numero_téléphone</label>
                            <textarea name="Numero_téléphone" class="form-control"><?php echo $Numero_téléphone; ?></textarea>
                            
                        </div>
                        

                        <div class="form-group <?php echo (!empty($adress_err)) ? 'has-error' : ''; ?>">
                            <label>Adresse_mail</label>
                            <input type="text" name="Adresse_mail" class="form-control" value="<?php echo $Adresse_mail; ?>">
                            
                        </div>
                        <input type="hidden" name="id_fiscale" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>