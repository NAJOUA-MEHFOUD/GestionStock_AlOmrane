<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du parc informatique</title>
      <style>
         body{
            background-image: url(Omrane.jpg);
            font-family: Arial, sans-serif;
            background-size: cover;
    
            
        }
        .container {
    width: 500px;
    height: 300px;
    margin: 0 auto; /* Centre verticalement et horizontalement */
    padding: 40px;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    box-shadow: 0 0 30px rgb(211, 214, 233);
}
        h1{
          text-align: center;
    font-size: 40px; /* Taille de police en pixels */
    font-style: italic;
    color: black; /* Couleur du texte */
        }
        #titre {
    text-align: center;
    font-size: 40px; /* Taille de police en pixels */
    font-style: italic; /* Texte en italique */
    color: black; /* Couleur du texte */
}
#conne{
  font-size: 20px;
  font-style: italic;
}
        
        .form-label {
            font-weight: bold;
        }
       

        .form-control {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left:180px;
        }
    </style>
    
</head>
<body>


<?php
session_start();



if(isset($_POST['connexion'])){
    $login = $_POST['login'];
    $password = $_POST['password'];
    
    if(!empty($login) && !empty($password)){
        require_once 'database.php';
        $sqlstate = $pdo->prepare('SELECT * FROM utilisateur WHERE login = ? AND password = ?');
        $sqlstate->execute([$login, $password]);

        if($sqlstate->rowCount() >= 1){
            $user = $sqlstate->fetch();
            $_SESSION['utilisateur'] = $user;
            $_SESSION['Matricule'] = $user['Matricule'];
        
            if ($user['type'] === 'Admin') {
                header('Location: admin.php');
                exit();
            } else {
                header('Location: user.php');
                exit();
            }
        }
         else {
            ?> 
            <div class="alert alert-danger" role="alert">
                Utilisateur invalide
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

<h1>Gestion du parc informatique </h1>
<div class="container">
    <h4 id="titre">Connexion</h4>
    <form method="post">
        <label class="form-label">Adresse e-mail</label>
        <input type="text" class="form-control" name="login">
        <label class="form-label">Mot de passe</label>
        <input type="password" class="form-control" name="password">
        <br><br>
        <input type="submit" value="Connexion" class="btn btn-primary my-2" name="connexion" id="conne">
    </form>
</div>

</body>
</html>