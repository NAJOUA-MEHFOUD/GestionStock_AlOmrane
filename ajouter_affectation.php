</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du parc informatique</title>
    <style>
        form{
            width:  600px;
            height: 300px; /* Make the form slightly longer */
            margin: 0 auto; /* pour centrer au milieu de la page*/
            padding: 60px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            margin-top: 100px;
            box-shadow: 0 0 30px rgb(86, 70, 224);
        }
        body {
            background-image: url(Omrane.jpg);
            background-size: cover;
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }

        .container {
            width: 600px;
            height: 400px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            box-shadow: 0 0 30px rgb(86, 70, 224);
            padding: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            font-style: italic;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
            width: 100%;
        }

        .form-group label {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            height: 35px;
            font-size: 20px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        #butt {
            text-decoration: none;
        padding: 10px 20px;
        background-color: #0b19b4;
        color: #fff;
        text-decoration: none;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        margin-left: 240px;
        }
        #nom_matriculee{
        font-size: 20px ;
        font-weight: bold;
        }
        #nom_inventairee{
        font-size: 20px ;
        font-weight: bold;
        }
        #p2{
        font-size: 20px ;
        font-weight: bold;
        }
        #p2{
        font-size: 20px ;
        font-weight: bold;
        }
        
        
    </style>
</head>
<body>
    <nav>
    <?php include "nav.php"?>
</nav>

<form action="traitement_formulaire.php" method="POST" id="affectationForm">

        <h2>Formulaire d'Affectation du Parc Informatique</h2>
        <label for="nom_matricule" id=nom_matriculee >Nom avec Matricule :</label>
        <select id="nom_matricule" name="nom_matricule">
            <?php
            $pdo = new PDO('mysql:host=localhost;dbname=parc_informatique', 'root', '');

            // Récupérer les options pour le nom avec matricule depuis la base de données (table "utilisateur")
            $sql_matricules = "SELECT * FROM utilisateur";
            $stmt_matricules = $pdo->query($sql_matricules);
            while ($row = $stmt_matricules->fetch()) {
                echo '<option value="' . $row['Matricule'] . '">' . $row['Nom'] . ' - ' . $row['Matricule'] . '</option>';
            }
            ?>
        </select><br><br> 
        <label for="nom_inventaire" id = nom_inventairee>Numéro avec Type d'inventaire :</label>
        <select id="nom_inventaire" name="nom_inventaire">
            <?php
            // Récupérer les options pour le numéro avec type d'inventaire depuis la base de données (table "materiel")
            $sql_inventaires = "SELECT CONCAT(Inventaire, ' - ', Type) AS Numero_Type FROM materiel where Etat='libre'";
            $stmt_inventaires = $pdo->query($sql_inventaires);
            while ($row = $stmt_inventaires->fetch()) {
                echo '<option value="' . $row['Numero_Type'] . '">' . $row['Numero_Type'] . '</option>';
            }
            ?>
        </select><br>
        <p id="p2">
            la date d'affectation :
            <input type="date" required id="d2" name="date">
        </p>
        <input type="submit" value="Valider" id="butt">
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById("affectationForm");

            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Empêche l'envoi du formulaire

                // Effectuer une requête AJAX pour envoyer les données du formulaire au script PHP de traitement
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // La requête s'est terminée avec succès
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Afficher l'alerte en cas de succès
                                alert(response.message);
                                form.reset(); // Réinitialise le formulaire après l'envoi
                            } else {
                                // Afficher l'alerte en cas d'erreur
                                alert(response.message);
                            }
                        } else {
                            // La requête a échoué
                            alert("Une erreur est survenue lors de la communication avec le serveur.");
                        }
                    }
                };

                var formData = new FormData(form);
                xhr.open("POST", form.action, true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send(new URLSearchParams(formData));
            });
        });
    </script>
    

    </form>
</body>
</html>