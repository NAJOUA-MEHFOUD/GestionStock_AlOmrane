<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        form{
            width:  600px;
            height: 380px; /* Make the form slightly longer */
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

        h3 {
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
        #css{
        font-size: 20px ;
        font-weight: bold;
        }
        #type{
        font-size: 20px ;
        font-weight: bold;
        }
        #marque{
        font-size: 20px ;
        font-weight: bold;
        }
        #date_achat{
        font-size: 20px ;
        font-weight: bold;
        }
        #fournisseur{
        font-size: 20px ;
        font-weight: bold;
        }
        
    </style>
</head>
<body>
<?php include 'nav.php' ?>
<?php
  if(isset($_POST['ajouter_materiel'])){
    $inventaire = $_POST['inventaire'];
    $type = $_POST['type'];
    $marque = $_POST['marque'];
    $date = $_POST['date'];
    $etat ="libre";
    $id_fournisseur = $_POST['id_fournisseur'];

    
    if(!empty($inventaire) && !empty($type) && !empty($marque) && !empty($date) && !empty($date) && !empty($etat) && !empty($id_fournisseur)){
      require_once 'database.php';
      $sql = $pdo->prepare('SELECT id_fiscale FROM fournisseur');
$sql->execute();
$id_fiscales = $sql->fetchAll(PDO::FETCH_COLUMN);

// Check if $id_fournisseur exists in the $id_fiscales array
if (in_array($id_fournisseur, $id_fiscales)) {
    // The $id_fournisseur exists in the array, so you can proceed with the insertion
    $sqlstate = $pdo->prepare('INSERT INTO materiel (Inventaire, Type, Marque, Date_achat, Etat, id_fiscale) VALUES (?, ?, ?, ?, ?, ?)');
    $sqlstate->execute([$inventaire, $type, $marque, $date, $etat, $id_fournisseur]);
} else {
    // The $id_fournisseur does not exist in the array, show an error message
    ?> 
      <div class="alert alert-danger" role="alert">
      le fournisseur avec id fiscale que vous avez entrez n'existe pas.
      </div>
      <?php
}
    }else{
      ?> 
      <div class="alert alert-danger" role="alert">
      Veuillez remplir les champs
      </div>
      <?php
    }

  }
  ?>

    <form method="post">
        <h3>Formulaire d'ajout des matériels au parc informatique</h3> 
        <p id="css">
            Numéro D'inventaire :
            <input type="text" required id="d1" name="inventaire">
        </p>
        <p id="type">
            Type :
            <select id="selecttype" name="type">
                <option value="Ordinateurs de bureau (Desktop)">Ordinateurs de bureau (Desktop)</option>
                <option value="Ordinateurs portables (Laptop)">Ordinateurs portables (Laptop)</option>
                <option value="Serveurs">Serveurs</option>
                <option value="Périphériques d'entrée/sortie">Périphériques d'entrée/sortie</option>
                <option value="Scanner">Scanner</option>
                <option value="Tablette">Tablette</option>
                <option value="Équipement de télécommunication">Station D'accueil</option>
                <option value="Imprimantes(noir/blanc)">Imprimantes(noir/blanc)</option>
                <option value="Imprimantes couleurs">Imprimantes couleurs</option>
            </select>
        </p>
        <p id="marque">Marque :
            <select id="select " name="marque" required>
                <option value="HP" id="d1">HP</option>
                <option value="DELL">DELL</option>
                <option value="ASUS">ASUS</option>
                <option value="APPELE">APPELE (MacBook)</option>
                <option value="MSI">MSI</option>
                <option value="SAMSUNG">SAMSUNG</option>
                <option value="LG">LG</option>
                <option value="MICRSOFT (Surface)">MICRSOFT (Surface)</option>
            </select>
        </p>
        <p id="date_achat">
        Date d'achat :
        <input type="date" required id="d4" name="date">
    </p>
            <p  id="fournisseur">
            Numéro fiscale du Fournisseur:
            <input type="text" required name="id_fournisseur">
            </p>
        
            <button type="submit" id="butt"   name="ajouter_materiel">Ajouter</button>
            </form>
</body>
</html>