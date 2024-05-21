<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du parc informatique</title>
<style>
        form{
            width:  600px;
            height: 430px; /* Make the form slightly longer */
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
        #Nom{
        font-size: 20px ;
        font-weight: bold;
        }
        #Prénom{
        font-size: 20px ;
        font-weight: bold;
        }
        #Numéro_de_téléphone{
        font-size: 20px ;
        font-weight: bold;
        }
        #Login{
        font-size: 20px ;
        font-weight: bold;
        }
        #Password{
        font-size: 20px ;
        font-weight: bold;
        }
        #Direction{
        font-size: 20px ;
        font-weight: bold;
        }
        #Type{
        font-size: 20px ;
        font-weight: bold;
        }
        .alert {
        background-color: #f8d7da; /* Background color for danger alert */
        color: #721c24; /* Text color for danger alert */
        border-color: #f5c6cb; /* Border color for danger alert */
        padding: 10px; /* Padding for the alert */
        border-radius: 5px; /* Rounded corners */
        margin: 10px 0; /* Margin to create space around the alert */
    }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav>
    <?php include 'nav.php' ?>
</nav>
    
<?php
  if(isset($_POST['Ajouter_Utilisateur'])){
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prénom'];
    $numero = $_POST['Numéro_de_téléphone'];
    $login = $_POST['Login'];
    $password = $_POST['Password'];
    $direction = $_POST['Direction'];
    $type = $_POST['Type'];


     if(!empty($nom) && !empty($prenom) && !empty($numero) && !empty($login) && !empty($password) && !empty($direction) && !empty($type)){
        require_once 'database.php';
        $sqlquery = $pdo->prepare('SELECT * from utilisateur WHERE Login = ? and Password =? ');
        $sqlquery->execute([$login, $password]);
        if ($sqlquery->rowCount() >= 1) {
            ?> 
            <div class="alert alert-danger" role="alert">
    Utilisateur déjà inscrit
   </div>
            <?php
        }else{
      $sqlState = $pdo->prepare('INSERT INTO utilisateur (Matricule, Nom, Prénom, Numero_téléphone, Login, Password, Direction, type) values(NULL,?,?,?,?,?,?,?)');
      $sqlState->execute([$nom,$prenom,$numero,$login, $password,$direction,$type]);
      //Redirection
      
    }
     }else{
      ?> 
      <div class="alert alert-danger" role="alert">
      Veuillez remplir les champs
      </div>
      <?php
     
   
  }
}
  //formulaire
  //type doit etre avec option qui contient soit admin soit utilisateur
  ?>
<form  method="post">
    <h3>Formulaire d'inscription des utilisateurs du parc informatique</h3> 
    <p id="Nom">
        Nom :
        <input type="text" required name="Nom">
    </p>
    <p id="Prénom">
        Prénom :
        <input type="text" required name="Prénom">
    </p>
    <p id="Numéro_de_téléphone">
        Numéro de téléphone:
        <input type="string" required name="Numéro_de_téléphone">
    </p>
    <p id="Login">
       Login :
       <input type="text" required name="Login">
    </p>
    <p id="Password">
    Password:
    <input type="password" required name="Password" id="Password">
</p>
    
    <p id="Direction">
        Direction :
        <select id="Direction" name="Direction" >
         <option>
             <option value="Direction Technique et Ingénierie">Direction Technique et Ingénierie</option>
             <option value="Direction Commerciale et Marketing">Direction Commerciale et Marketing</option>
             <option value="Direction Organisation et Capitale Humain">Direction Organisation et Capitale Humain</option>
             <option value="Direction Financiére et Controle de Gestion">Direction Financiére et Controle de Gestion </option>
             <option value="Agence Kenitra">Agence Kenitra</option> 
             <option value="Agence Temara">Agence Temara</option> 
             <option value="Agence Salé">Agence Salé</option> 
             <option value="Agence Khémisset">Agence Khémisset</option>
             <option value="Agence Sidi Kacem Sidi Slimane">Agence Sidi Kacem Sidi Slimane</option> 
             <option value="Agence Tamesna">Agence Tamesna</option>  
         </option>
        </select>
     </p>
     <p id="Type">
        Type :
        <select id="Type" name="Type" >
         <option>
             <option value="Utilisateur">Utilisateur</option>
             <option value="Admin">Admin</option>
         </option>
        </select><br><br>

     <button type="submit" id="butt"   name="Ajouter_Utilisateur">Ajouter</button>
  </form>
 

 
</body>
</html>