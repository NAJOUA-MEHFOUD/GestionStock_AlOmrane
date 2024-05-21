<?php
// Process delete operation after confirmation
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    // Include config file
    require_once 'config.php';

    // Check if there are related records in the "affectation" table
    $sql_check_related = "SELECT COUNT(*) FROM affectation WHERE Matricule = ?";
    if ($stmt_check_related = $mysqli->prepare($sql_check_related)) {
        $stmt_check_related->bind_param("i", $param_id);
        $param_id = trim($_GET["id"]);
        $stmt_check_related->execute();
        $stmt_check_related->bind_result($related_count);
        $stmt_check_related->fetch();
        $stmt_check_related->close();

        if ($related_count > 0) {
            // There are related records in the "affectation" table, so delete them first
            $sql_delete_affectation = "DELETE FROM affectation WHERE Matricule = ?";
            if ($stmt_delete_affectation = $mysqli->prepare($sql_delete_affectation)) {
                $stmt_delete_affectation->bind_param("i", $param_id);
                $stmt_delete_affectation->execute();
                $stmt_delete_affectation->close();
            }
        }
    }

    // Now, proceed to delete the record from the "materiel" table
    $sql_delete_materiel = "DELETE FROM utilisateur WHERE Matricule = ?";
    if ($stmt_delete_materiel = $mysqli->prepare($sql_delete_materiel)) {
        $stmt_delete_materiel->bind_param("i", $param_id);
        $param_id = trim($_GET["id"]);
        if ($stmt_delete_materiel->execute()) {
            // Records deleted successfully. Redirect to landing page
            header("location: Lister_utilisateur.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt_delete_materiel->close();
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
                        <h1>Delete Record</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="Matricule" value="<?php echo trim($_POST["Matricule"]); ?>">
                            <p>Are you sure you want to delete this record?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="Lister_utilisateur.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>