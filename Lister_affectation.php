<!DOCTYPE html>
<html lang="en">
<head>
<?php include "nav.php"?>
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
        /* Ajoutez le style pour le bouton Delete */
        .delete-btn {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Style au survol (hover) du bouton Delete */
        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<nav>
    <?php include "nav.php"?>
    </nav>
    <br></br>
    <br></br>
    <br></br>
    <br></br>
<!-- Ajoutez un bouton avec l'ID "btnGenerateExcel" -->
<button id="btnGenerateExcel">Générer le fichier Excel</button>
<!-- Ajoutez ce code JavaScript à votre page -->
<script>
document.getElementById("btnGenerateExcel").addEventListener("click", function() {
    // Créez une requête AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "generate_excel_affectation.php", true);
    xhr.responseType = "blob"; // Le serveur renverra le fichier sous forme de blob (binaire)

    // Lorsque la réponse AJAX est prête
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Créez un lien pour télécharger le fichier
            var blob = new Blob([xhr.response], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            var url = URL.createObjectURL(blob);
            var a = document.createElement("a");
            a.href = url;
            a.download = "Liste_Affectation.xlsx";
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

<!-- Votre code HTML pour la recherche et le tableau -->
<?php
require_once 'database.php';
?>
<p id="rbatia">
    Rechercher par :
    <select id="selectSearch" name="searchType" required title="Type de recherche">
        <option value="Matricule">Matricule</option>
        <option value="Inventaire">Inventaire</option>
        <option value="Data_aff">Date affectation</option>
        <option value="Data_aff">Date fin d'affectation</option>
    </select>
    <input type="text" id="searchValue" placeholder="Entrez la valeur de recherche"><br><br>
    <button onclick="rechercherNumeroInventaire()" id="rech">Rechercher</button>
</p>

<br>
<table class="tableau">
    <thead>
        <tr>
            <th>Matricule</th>
            <th>Inventaire</th>
            <th>Date Affectation</th>
            <th>Date fin d'affectation</th>
            <th>Action</th>   
        </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM affectation";
    $result = $pdo->query($sql);
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "
                <tr>
                    <td>{$row['Matricule']}</td>
                    <td>{$row['Inventaire']}</td>
                    <td>{$row['Data_aff']}</td>
                    <td>{$row['Date_fin']}</td>
                    <td>
                    <button class='btn btn-danger btn-sm delete-btn' data-matricule='{$row['Matricule']}' data-inventaire='{$row['Inventaire']}'>Terminer Affectation</button>
                    </td>
                </tr>
            ";
        }
    } else {
        echo "<tr><td colspan='4'>Aucun résultat trouvé.</td></tr>";
    }
    ?> 
    </tbody>
</table>

<!-- Votre code JavaScript -->
<script>
// Function to send the AJAX request to delete the affectation
// Mettez à jour le code JavaScript pour le bouton "Terminer Affectation"
function terminerAffectation(button) {
    var row = button.parentNode.parentNode;
    var matriculeValue = row.cells[0].textContent.trim();
    var inventaireValue = row.cells[1].textContent.trim();

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_affectation.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Mise à jour réussie
                row.cells[3].textContent = response.newDateFin; // Mettez à jour la cellule Date fin dans le tableau
                button.disabled = true; // Désactivez le bouton après la mise à jour
                console.log("Affectation terminée avec succès !");
            } else {
                // Gérer le cas d'erreur
                console.log("Échec de la terminaison de l'affectation !");
            }
        }
    };

    // Envoyez les données de matricule et d'inventaire
    xhr.send("matricule=" + encodeURIComponent(matriculeValue) + "&inventaire=" + encodeURIComponent(inventaireValue));
}

// Ajoutez un seul écouteur d'événements pour tous les boutons de terminaison d'affectation
document.addEventListener("click", function(event) {
    if (event.target.classList.contains("delete-btn")) {
        terminerAffectation(event.target);
    }
});

// Function to search for affectations based on user input
function rechercherNumeroInventaire() {
    var selectElement = document.getElementById("selectSearch");
    var searchType = selectElement.value;
    var searchValue = document.getElementById("searchValue").value.trim();

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "search_affectation.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            afficherResultatsRecherche(response);
        }
    };
    xhr.send("searchType=" + encodeURIComponent(searchType) + "&searchValue=" + encodeURIComponent(searchValue));
}

// Function to display search results in the table
function afficherResultatsRecherche(results) {
    var tbody = document.querySelector(".tableau tbody");
    tbody.innerHTML = "";

    if (results.length > 0) {
        for (var i = 0; i < results.length; i++) {
            var row = document.createElement("tr");
            var data = results[i];

            row.innerHTML = `
                <td>${data["Matricule"]}</td>
                <td>${data["Inventaire"]}</td>
                <td>${data["Data_aff"]}</td>
                <td>${data["Date_fin"]}</td>
                <td>
                <button class='btn btn-danger btn-sm delete-btn' data-matricule='{$row['Matricule']}' data-inventaire='{$row['Inventaire']}'>Terminer Affectation</button>
                </td>
            `;

            tbody.appendChild(row);
        }
    } else {
        var emptyRow = document.createElement("tr");
        emptyRow.innerHTML = "<td colspan='4'>Aucun résultat trouvé.</td>";
        tbody.appendChild(emptyRow);
    }
}

// Ajoutez un seul écouteur d'événements pour tous les boutons de suppression

</script>

</body>
</html>