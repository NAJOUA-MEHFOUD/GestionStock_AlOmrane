</html><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du parc informatique</title>
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
        #p0{
        font-size: 20px ;
        font-weight: bold;
        }
        #p0{
        font-size: 20px ;
        font-weight: bold;
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
        
    </style>
</head>
<body>
    <nav>
        <?php include "nav.php"?>
    </nav>
    
<?php
if (isset($_POST['ajouter'])) {
    $id_fiscale = $_POST['id_fiscale'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $numero = $_POST['numero'];
    $adresse_mail = $_POST['adresse_mail'];

    if (!empty($nom) && !empty($prenom) && !empty($numero) && !empty($adresse_mail)) {
        require_once 'database.php';
        $sqlquery = $pdo->prepare('SELECT * from fournisseur WHERE id_fiscale = ?');
        $sqlquery->execute([$id_fiscale]);

        if ($sqlquery->rowCount() >= 1) {
            ?> 
            <div class="alert alert-danger" role="alert">
                L'identifiant fiscale du fournisseur existe déjà
            </div>
            <?php
        } else {
            $sqlState = $pdo->prepare('INSERT INTO fournisseur (id_fiscale, Nom, Prenom, Numero_téléphone, Adresse_mail) VALUES (?, ?, ?, ?, ?)');
            $sqlState->execute([$id_fiscale, $nom, $prenom, $numero, $adresse_mail]);
            // Display a success message
            ?> 
            <div class="alert alert-success" role="alert">
                Fournisseur ajouté avec succès !
            </div>
            <?php
        }
    } else {
        ?> 
        <div class="alert alert-danger" role="alert">
            Veuillez remplir tous les champs
        </div>
        <?php
    }
}
?>
    <form method ="post">
        <h3>Fourmulaire d'ajouter des fournisseur du parc informatique</h3>
        <p id="p0">
            Numéro_Fiscale:
            <input type="text"  required name="id_fiscale">
        </p>
        <p id="p1">
            Nom:
            <input type="text"  required name="nom">
        </p>
        <p id="p2">
            Prénom:
            <input type="text"  required name="prenom">
        </p>
        <p id="p3">
            Numéro_téléphone:
            <input type="text"  required name="numero">
        </p>
        <p id="p4">
            Adresse_Email:
            <input type="text"  required name="adresse_mail">
        </p>
        <button type="submit" id="butt"   name="ajouter">Ajouter</button>
    </form>
</body>
</html>