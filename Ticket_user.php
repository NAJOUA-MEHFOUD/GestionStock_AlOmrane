<!DOCTYPE html>
<html lang="en">
<head>
    <style>
      
    
      img {
            width: 200px;
            height: 100px;
            background-size: cover;
        }
        html {
            overflow: hidden;
        }
        body {
            background-image: url(Omrane.jpg);
            font-family: Arial, sans-serif;
            background-size: cover;
            padding: 50px;
        }
        form {
            width: 500px;
            height: 400px; /* Augmentez la hauteur du formulaire */
            margin: 0 auto; /* Déplacez le formulaire plus haut */
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            box-shadow: 0 0 30px rgb(211, 214, 233);
                 margin-bottom: 20px;
        }
        h2 {
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            font-style: italic;
        }

#p1{
    font-size: 20px ;
    font-weight: bold;
}
#p2{
    font-size: 20px ;
    font-weight: bold;
}
#p3{
    font-size: 20px ;
    font-weight: bold;
}
#p4{
    font-size: 20px ;
    font-weight: bold;
}
#p5{
    font-size: 20px ;
    font-weight: bold;
}
#p6{
    font-size: 20px ;
    font-weight: bold;
}
#p7{
    font-size: 20px ;
    font-weight: bold;
}
#p8{
    font-size: 20px ;
    font-weight: bold;
}
#des{
    
    font-size: 20px ;
    font-weight: bold;
}#rés{
    font-size: 20px ;
    font-weight: bold; 
}
#dd{
    text-decoration: none;
  padding: 10px 20px;
  background-color: #0b19b4;
  color: #fff;
  text-decoration: none;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  margin-left: 200px;
}
#ajouter{
    text-decoration: none;
  padding: 10px 20px;
  background-color: #091dd3;
  color: #fff;
  text-decoration: none;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  margin-left: 190px;

}


#d1{
    font-size: 20px;
}
#d2{
    font-size: 20px;
}
#d3{
    font-size: 20px;
    width:280px;
}
#d4{
    font-size: 20px;  
    width:300px;
}

#date1{
    font-size: 20px;
}
#date2{
    font-size: 20px;
}
#zzz{
    font-size:20px;
    width:170px;

}
#jj{
    font-size: 20px ;
    font-weight: bold;
}
#ll{
    font-size: 20px ;
    font-weight: bold;
}
form{
    font-style:italic;
}



    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du parc informatique</title>
</head>
<body>

<?php
require_once 'database.php';
session_start();

// Get the Matricule from the session
if (isset($_SESSION['Matricule'])) {
    $Matricule = $_SESSION['Matricule'];
} else {
    echo "Error: Matricule not set in the session.";
    exit;
}

function getAssignedInventaires($pdo, $Matricule)
{
    $sql = "SELECT DISTINCT a.Inventaire
            FROM affectation a
            WHERE a.Matricule = :Matricule";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':Matricule' => $Matricule]);
    $inventaires = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return $inventaires;
}

// Get assigned inventaires for the user
$Inventaires = getAssignedInventaires($pdo, $Matricule);

// Handle form submission
if (isset($_POST['ajouter'])) {
    $Titre = $_POST['Titre'];
    $Priorité = $_POST['Priorité'];
    $Categorie = $_POST['Categorie'];
    $Date_creation = date('Y-m-d');
    $Status = "ouvert";
    $Description = $_POST['Description'];

    if (!empty($Titre) && !empty($Priorité) && !empty($Categorie) && !empty($Status) && !empty($Description) && !empty($_POST['Inventaire'])) {
        $Inventaire = $_POST['Inventaire'];

        $sqlstate = $pdo->prepare('INSERT INTO ticket (Titre, Priorité, Categorie, Date_creation, Status, Description, Matricule, Inventaire) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $sqlstate->execute([$Titre, $Priorité, $Categorie, $Date_creation, $Status, $Description, $Matricule,  $Inventaire]);
?>
        <div class="alert alert-success" role="alert">
            Ticket ajouté avec succès<br>
            تمت إضافة التذكرة بنجاح
        </div>
<?php
    } else {
?>
        <div class="alert alert-danger" role="alert">
            Veuillez remplir tous les champs
        </div>
<?php
    }
}
?>
    <form method="POST">
        <h2>Créer un ticket</h2>
<p id="p1">
    Titre de ticket :
    <input type="text" required id="d2" name="Titre">
</p>
<p id="ll">
Priorité:
<select id="d4" name="Priorité">
    <option value="Basse">Basse</option>
    <option value="Moyenne">Moyenne</option>
    <option value="Elevée">Elevée</option>
    <option value="Critique">Critique</option>
</select>
</p>
<p id="jj">
Categorie:
<select id="d3" name="Categorie">
    <option value="Matériel">Matériel</option>
    <option value="logiciel">logiciel</option>
    <option value="Réseau">Réseau</option>
    <option value="demande d'accès">demande d'accès</option>
</select>
</p>
       
        <p id="p6">
        Inventaire du matériel
        <select id="zzz" name="Inventaire">
            <?php
            foreach ($Inventaires as $inventaire) {
                echo "<option value='$inventaire'>$inventaire</option>";
            }
            ?>
        </select>
    </p>
    </p>
        <label for="Description" id="des" name="Description">Description:</label>
        <textarea id="Description" name="Description" rows="4" cols="50"></textarea><br>
        <input type="submit" value ="ajouter" class="btn btn-primary my-2" name="ajouter" id="ajouter">

    </form>
    <br>
     
</body>
</html>