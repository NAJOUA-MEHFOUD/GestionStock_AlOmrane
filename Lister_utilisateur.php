<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'nav.php' ?>
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
        /**hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh */
        /* Styles pour les boutons Modifier et Supprimer */
.btn-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn {
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    color: #fff;
    cursor: pointer;
    margin: 5px;
}

.btn-edit {
    background-color: #007bff; /* Couleur de fond du bouton Modifier */
    border: 1px solid #007bff; /* Bordure du bouton Modifier */
}

.btn-delete {
    background-color: #dc3545; /* Couleur de fond du bouton Supprimer */
    border: 1px solid #dc3545; /* Bordure du bouton Supprimer */
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
    xhr.open("GET", "generate_excel_utilisateur.php", true);
    xhr.responseType = "blob"; // Le serveur renverra le fichier sous forme de blob (binaire)

    // Lorsque la réponse AJAX est prête
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Créez un lien pour télécharger le fichier
            var blob = new Blob([xhr.response], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            var url = URL.createObjectURL(blob);
            var a = document.createElement("a");
            a.href = url;
            a.download = "Liste_Utilisateur.xlsx";
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
    <p id="rbatia">
    Rechercher par :
    <select id="selectSearch" name="searchType" required title="Type de recherche">
    
    <option value="Nom">Nom</option>
    <option value="Prenom">Prénom</option>
    <option value="Numero_téléphone">Numéro de téléphone</option>
    <option value="Login">Adresse Email</option>
    <option value="Direction">Direction</option>
    <option value="type">Type</option>
</select>

    <input type="text" id="searchValue" placeholder="Entrez la valeur de recherche"><br><br>
    <button onclick="rechercherNumeroMatricule() " id="rech">Rechercher</button>
</p>


    <br>
    <table class="tableau">
        <thead>
            <tr>
                
                <th>Nom</th>
                <th>Prénom</th>
                <th>Numero de téléphone</th>
                <th>Adresse mail</th>
                <th>Direction</th>
                <th>Type</th>
                <th>Action</th>   
    
            </tr>
        </thead>
       <!-- ... (Code HTML précédent inchangé) ... -->
<tbody>
    <?php
    require_once 'database.php';
    $sql = "SELECT * FROM utilisateur";
    $result = $pdo->query($sql);
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "
            <tr>
                
                <td>{$row['Nom']}</td>
                <td>{$row['Prénom']}</td>
                <td>{$row['Numero_téléphone']}</td>
                <td>{$row['Login']}</td>
                <td>{$row['Direction']}</td>
                <td>{$row['type']}</td>
                <td>
                    <div class='btn-container'>
                        <a class='btn btn-edit btn-sm' href='/Omrane_Stage/edit_utilisateur.php?id={$row['Matricule']}'>Modifier</a>
                        <a class='btn btn-delete btn-sm' href='/Omrane_Stage/delete_utilisateur.php?id={$row['Matricule']}'>Supprimer</a>
                    </div>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>Aucun résultat trouvé.</td></tr>";
    }
    ?> 
</tbody>
<!-- ... (Code HTML précédent inchangé) ... -->

    </table>

    <script>


document.getElementById("btnGenerateExcel").addEventListener("click", function() {
        // Le code pour générer le fichier Excel, inchangé
    });

    function supprimerUtilisateur(button) {
        var row = button.closest('.search-result-row');
        var primaryKeyValue = row.cells[0].textContent.trim();

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_utilisateur.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Suppression réussie
                    row.parentNode.removeChild(row);
                    console.log("Suppression réussie !");
                } else {
                    // Gérer le cas d'erreur
                    console.log("Échec de la suppression !");
                }
            }
        };
        xhr.send("primaryKey=" + encodeURIComponent(primaryKeyValue));
    }

    function getColumnName(cellIndex) {
        var columnNames = ["Matricule", "Nom", "Prénom", "Numéro de téléphone", "Adresse mail", "Direction","Type"];
        return columnNames[cellIndex];
    }

    function rechercherNumeroMatricule() {
    var selectElement = document.getElementById("selectSearch");
    var searchType = selectElement.value;
    var searchValue = document.getElementById("searchValue").value.trim();

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "search_utilisateur.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            afficherResultatsRecherche(response);
        }
    };
    xhr.send("searchType=" + encodeURIComponent(searchType) + "&searchValue=" + encodeURIComponent(searchValue));
}

function afficherResultatsRecherche(results) {
    var tbody = document.querySelector(".tableau tbody");
    tbody.innerHTML = "";

    if (results.length > 0) {
        for (var i = 0; i < results.length; i++) {
            var row = document.createElement("tr");
            var rowData = results[i];

            row.innerHTML = `
                
                <td>${rowData["Nom"]}</td>
                <td>${rowData["Prénom"]}</td>
                <td>${rowData["Numero_téléphone"]}</td>
                <td>${rowData["Login"]}</td>
                <td>${rowData["Direction"]}</td>
                <td>${rowData["type"]}</td>
                <td>
                    <div class='btn-container'>
                        <a class='btn btn-edit btn-sm' href='/Omrane_Stage/edit_utilisateur.php?id=${rowData["Matricule"]}'>Modifier</a>
                        <a class='btn btn-delete btn-sm' href='/Omrane_Stage/delete_utilisateur.php?id=${rowData["Matricule"]}'>Supprimer</a>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        }
    } else {
        var emptyRow = document.createElement("tr");
        emptyRow.innerHTML = "<td colspan='8'>Aucun résultat trouvé.</td>";
        tbody.appendChild(emptyRow);
    }
}

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-delete')) {
            supprimerUtilisateur(event.target);
        }
    });
    </script>
</body>
</html>