<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du parc informatique</title>
    <style>
        .btn-danger {
    background-color: #007bff; /* Couleur de fond du bouton "Supprimer" */
    color: #fff; /* Couleur du texte du bouton "Supprimer" */
}

       
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

        /* Boutons "Edit" et "Supprimer" */
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #007bff; /* Couleur de fond du bouton "Edit" */
        }

        .btn-danger {
            background-color: #dc3545; /* Couleur de fond du bouton "Supprimer" */
        }

        /* Ajouter une marge entre les boutons */
        td a.btn {
            margin-right: 5px;
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
<button id="btnGenerateExcel">Générer le fichier Excel</button>
    <script>
        document.getElementById("btnGenerateExcel").addEventListener("click", function() {
            // Créez une requête AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "generate_excel_Ticket.php", true);
            xhr.responseType = "blob"; // Le serveur renverra le fichier sous forme de blob (binaire)

            // Lorsque la réponse AJAX est prête
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Créez un lien pour télécharger le fichier
                    var blob = new Blob([xhr.response], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
                    var url = URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = url;
                    a.download = "Liste_Ticket.xlsx";
                    document.body.appendChild(a);

                    // Cliquez sur le lien pour déclencher le téléchargement
                    a.click();

                    // Supprimez le lien après le téléchargement
                    URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                } else {
                    console.log("Erreur lors de la génération du fichier Excel.");
                }
            };

            // En cas d'erreur lors de la requête AJAX
            xhr.onerror = function() {
                console.log("Erreur de requête AJAX.");
            };

            // Envoyez la requête AJAX
            xhr.send();
        });
    </script>
<?php include 'nav.php' ?>
    <?php require_once 'database.php'; ?>
    <p id="rbatia">
        Rechercher par :
        <select id="selectSearch" name="searchType" required title="Type de recherche">
            <option value="Id_ticket">Id ticket</option>
            <option value="Titre">Titre</option>
            <option value="Priorité">Priorité</option>
            <option value="Categorie">Catégorie</option>
            <option value="Date_creation">Date création</option>
            <option value="status">Status</option>
            <option value="Description">Description</option>
        </select>
        <input type="text" id="searchValue" placeholder="Entrez la valeur de recherche"><br><br>
        <button onclick="rechercherNumeroId()" id="rech">Rechercher</button>
    </p>

    <br>
    <table class="tableau" id="tableau">
        <thead>
            <tr>
                <th>Id ticket</th>
                <th>Titre</th>
                <th>Priorité</th>
                <th>Catégorie</th>
                <th>Date création</th>
                <th>Status</th>
                <th>Description</th>
                <th>Action</th>   
            </tr>
        </thead>
        <tbody>
            
            <?php
            $sql = "SELECT * FROM ticket";
            $result = $pdo->query($sql);
            if ($result->rowCount() > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "
                    <tr>
                        <td>{$row['Id_ticket']}</td>
                        <td>{$row['Titre']}</td>
                        <td>{$row['Priorité']}</td>
                        <td>{$row['Categorie']}</td>
                        <td>{$row['Date_creation']}</td>
                        <td>{$row['Status']}</td>
                        <td>{$row['Description']}</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='/Omrane_Stage/edit_ticket.php?id={$row['Id_ticket']}'>Edit</a>
                            <a class='btn btn-danger btn-sm' onclick='supprimerTicket(this)'>Supprimer</a>
                        </td>
                    </tr>
                    ";
                }
            } else {
                echo "<tr><td colspan='10'>Aucun résultat trouvé.</td></tr>";
            }
            ?> 
        </tbody>
    </table>

    <script>
        function supprimerTicket(button) {
            var row = button.parentNode.parentNode;
            var primaryKeyValue = row.cells[0].textContent.trim();

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_ticket.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        row.parentNode.removeChild(row);
                        console.log("Suppression réussie !");
                    } else {
                        console.log("Échec de la suppression !");
                    }
                }
            };
            xhr.send("primaryKey=" + encodeURIComponent(primaryKeyValue));
        }

        function rechercherNumeroId() {
            var selectElement = document.getElementById("selectSearch");
            var searchType = selectElement.value;
            var searchValue = document.getElementById("searchValue").value.trim();

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "search_ticket.php", true);
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
            var tbody = document.querySelector("#tableau tbody");
            tbody.innerHTML = "";

            if (results.length > 0) {
                for (var i = 0; i < results.length; i++) {
                    var row = document.createElement("tr");
                    var rowData = results[i];

                    row.innerHTML = `
                        <td>${rowData["Id_ticket"]}</td>
                        <td>${rowData["Titre"]}</td>
                        <td>${rowData["Priorité"]}</td>
                        <td>${rowData["Categorie"]}</td>
                        <td>${rowData["Date_creation"]}</td>
                        <td>${rowData["status"]}</td>
                        <td>${rowData["Description"]}</td>
                        <td>${rowData["Matricule"]}</td>
                        <td>${rowData["Inventaire"]}</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='/Omrane_Stage/edit_ticket.php?id=${rowData["Id_ticket"]}'>Edit</a>
                            <a class='btn btn-danger btn-sm' onclick='supprimerTicket(this)'>Supprimer</a>
                        </td>
                    `;

                    tbody.appendChild(row);
                }
            } else {
                var emptyRow = document.createElement("tr");
                emptyRow.innerHTML = "<td colspan='10'>Aucun résultat trouvé.</td>";
                tbody.appendChild(emptyRow);
            }
        }
    </script>
</body>
</html>