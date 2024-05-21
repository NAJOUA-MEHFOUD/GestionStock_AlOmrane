<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du parc informatique</title>
    <style>
        body{
            background-image: url(tab.jpg);
            background-size: cover;
            
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-style: italic;
            font-size: 25px;
        }
        #rech {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #091dd3;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            margin-left: 400px;
        }
        #rbatia {
            font-size: 25px;
            font-weight: bold;
            font-style: italic;
        }
        .editable-cell {
            cursor: pointer;
        }
        .edit-buttons {
            display: none;
        }
    </style>
</head>
<body>

<?php
session_start();
$Matricule = $_SESSION['Matricule'];
?>  


    <!-- Ajoutez un bouton avec l'ID "btnGenerateExcel" -->
<button id="btnGenerateExcel">Générer le fichier Excel</button>
<br>
    </br>
<!-- Ajoutez ce code JavaScript à votre page -->
<script>
document.getElementById("btnGenerateExcel").addEventListener("click", function() {
    // Créez une requête AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "generate_excel_materiel.php", true);
    xhr.responseType = "blob"; // Le serveur renverra le fichier sous forme de blob (binaire)

    // Lorsque la réponse AJAX est prête
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Créez un lien pour télécharger le fichier
            var blob = new Blob([xhr.response], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            var url = URL.createObjectURL(blob);
            var a = document.createElement("a");
            a.href = url;
            a.download = "Liste_Materiel.xlsx";
            document.body.appendChild(a);

            // Cliquez sur le lien pour déclencher le téléchargement
            a.click();

            // Supprimez le lien après le téléchargement
            URL.revokeObjectURL(url);
            document.body.removeChild(a);
        }
    };

    // Envoyez la requête AJAX
    xhr.send();
});
</script>
    <?php
    require_once 'database.php';
    ?>

    <br>
    <table class="tableau">
        <thead>
            <tr>
                <th>Inventaire</th>
                <th>Type</th>
                <th>Marque</th>
                <th>Date achat</th>
                <th>Etat</th>
                <th>id fiscale</th>   
    
            </tr>
        </thead>
        <tbody>
        <?php



        require_once 'database.php';

// Assuming you have a table named 'affectation' with columns 'Matricule' and 'materiel'

        require_once 'database.php';
        $sql = "SELECT m.* FROM materiel m
        JOIN affectation a ON m.Inventaire = a.Inventaire
        WHERE a.Matricule = '$Matricule'";
        $result = $pdo->query($sql);
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo"
        <tr>
            <td> $row[Inventaire]</td>
            <td> $row[Type]</td>
            <td> $row[Marque]</td>
            <td> $row[Date_achat]</td>
            <td> $row[Etat]</td>
            <td> $row[id_fiscale]</td>
    
        </tr>
        ";
            }
        } else {
            echo "<tr><td colspan='6'>Aucun résultat trouvé.</td></tr>";
        }
        ?> 
        </tbody>
    </table>

    <script>



        function getColumnName(cellIndex) {
            var columnNames = ["Inventaire", "Type", "Marque", "Date_achat", "Etat", "id_fiscale"];
            return columnNames[cellIndex];
        }
       




    </script>
</body>
</html>