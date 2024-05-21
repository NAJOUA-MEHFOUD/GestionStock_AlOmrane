<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
         :root
        {
            --pink:#e79ae7;
            --green :#d7fecf;
            --blue:#90c6b9;
        }

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            text-decoration: none;
        }

        html{
            font-size: 62.5%;
            scroll-behavior: smooth;
        }

        body{
            background-image: url('Omrane.jpg');
            background-size: cover;
        }
    
        h2{
            font-size: 50px;
            color: rgb(8, 8, 8);
            text-align: center;
            margin-top: 600px;
            font-weight: bold;
        }

        p{
            font-size: 40px;
            text-align: center;
            font-weight: bold;
        }

        .nav{
            width: 100%;
            height: auto; 
            padding: 1rem;
            background: black;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed ;
            top: 0; 
            left: 0;
            z-index: 10000;
        }

        .nav a{
            color: white;
            font-size: 1.8rem;
            padding-left: 2rem;
            font-size:  30px;
            font-weight: lighter;
            margin-right: 20rem
        }
    </style>
</head>
<body>
<div class="container">
    <?php
     session_start();
    
    if(!isset($_SESSION['utilisateur']))
    
    header(header:'location:connexion.php');
    ?>

   <h3>Bonjour : <?php echo $_SESSION['utilisateur']['login'] ?></h3>
</div>
    <nav class="nav">
        <img src="alomrane_logo.png" width="100px" height="100px">
        <div>
            <a href="Ticket_user.php">créer ticket</a>
            <a href="Inventaire.php">Matériel</a>
            <a href="deconnexion.php">déconnexion</a>
        </div>
    </nav>
</body>
</html>