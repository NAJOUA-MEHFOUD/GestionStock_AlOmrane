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
        <!-- Ajoutez un bouton avec l'ID "btnGenerateExcel" -->
<button id="btnGenerateExcel">Générer le fichier Excel</button>
<!-- Ajoutez ce code JavaScript à votre page -->
<script>
document.getElementById("btnGenerateExcel").addEventListener("click", function() {
    // Créez une requête AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "generate_excel_fournisseur.php", true);
    xhr.responseType = "blob"; // Le serveur renverra le fichier sous forme de blob (binaire)

    // Lorsque la réponse AJAX est prête
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Créez un lien pour télécharger le fichier
            var blob = new Blob([xhr.response], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            var url = URL.createObjectURL(blob);
            var a = document.createElement("a");
            a.href = url;
            a.download = "Liste_Fournisseur.xlsx";
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
    <option value="Adresse_mail">Adresse Email</option>
</select>

    <input type="text" id="searchValue" placeholder="Entrez la valeur de recherche">
    <button onclick="rechercherNumeroInventaire() " id="rech">Rechercher</button>
</p>


    <br>
    <table class="tableau" id="tableau">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Numero de téléphone</th>
                <th>Adresse email</th>
                <th>Action</th>   
    
            </tr>
        </thead>
        <tbody>
        <?php
        require_once 'database.php';
        $sql = "SELECT * FROM fournisseur";
        $result = $pdo->query($sql);
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo"
        <tr>
            
            <td> $row[Nom]</td>
            <td> $row[Prenom]</td>
            <td> $row[Numero_téléphone]</td>
            <td> $row[Adresse_mail]</td>
            <td>
            <a class = 'btn btn-primary btn-sm' href='/Omrane_stage/Edit_fournisseur2.php?id=$row[id_fiscale]'>Edit</a>
            <button onclick='supprimerFournisseur(this)'>Supprimer</button>
            </td>
    
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


function supprimerFournisseur(button) {
            var row = button.parentNode.parentNode;
            var primaryKeyValue = row.cells[0].textContent.trim();

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete.php", true);
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

        function rechercherNumeroInventaire() {
            var selectElement = document.getElementById("selectSearch");
            var searchType = selectElement.value;
            var searchValue = document.getElementById("searchValue").value.trim();

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "search_fournisseur.php", true);
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
                    var data = results[i];

                    row.innerHTML = `
                        
                        <td>${data["Nom"]}</td>
                        <td>${data["Prenom"]}</td>
                        <td>${data["Numero_téléphone"]}</td>
                        <td>${data["Adresse_mail"]}</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='/Omrane_Stage/Edit_fournisseur2.php?id=${data["id_fiscale"]}'>Edit</a>
                            <button onclick='supprimerFournisseur(this)' class='btn btn-danger btn-sm'>Supprimer</button>
                        </td>
                    `;

                    tbody.appendChild(row);
                }
            } else {
                var emptyRow = document.createElement("tr");
                emptyRow.innerHTML = "<td colspan='6'>Aucun résultat trouvé.</td>";
                tbody.appendChild(emptyRow);
            }
        }
    </script>
</body>
</html>