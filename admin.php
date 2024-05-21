<!DOCTYPE html>
<html lang="en">
<head>
<style>
    :root {
        --pink: #e79ae7;
        --green: #d7fecf;
        --blue: #90c6b9;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        outline: none;
        text-decoration: none;
    }

    html {
        font-size: 62.5%;
        scroll-behavior: smooth;
    }

    body {
        background-image: url('Omrane.jpg');
        background-size: cover;
    }

    .nav {
        width: 100%;
        height: auto;
        padding: 1rem;
        background: black;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 10000;
    }

    .nav ul {
        display: flex;
        list-style: none;
        font-style: italic;
    }

    .nav ul li {
        padding: 0 1rem;
    }

    .nav a {
        color: white;
        font-size: 1.8rem;
        text-decoration: none;
        font-weight: lighter;
        font-size: 25px;
    }

    .dropdown {
        position: relative;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: black;
        min-width: 160px;
        z-index: 1;
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 12px;
    }

    .dropdown:hover .dropdown-content {
        display: flex;
    }

    .dropdown-content a {
        color: white;
        padding: 6px 12px;
        text-decoration: none;
        display: block;
        margin: 0 10px;
    }

    .menu-content {
        display: none;
    }

    .dropdown:hover .menu-content {
        display: flex;
    }
</style>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestion du parc informatique</title>
</head>
<body>
<nav class="nav">
    <img src="alomrane_logo.png" width="100px" height="100px">


<ul>
    <li class="dropdown">
        <a href="#" class="dropbtn">Créer ticket</a>
        <div class="dropdown-content menu-content"> <!-- Ajoutez la classe "menu-content" ici -->
            <a href="Ticket.php" class="ticket-option">Ajouter ticket</a>
            <a href="lister_ticket.php" class="ticket-option">Lister ticket</a>
        </div>
    </li>
</ul>



    <ul>
        <li class="dropdown">
            <a href="#" class="dropbtn">Matériel</a>
            <div class="dropdown-content">
                <div class="menu-content">
                    <a href="ajouter_materiel.php" class="materiel-option">Ajouter matériel</a>
                    <a href="lister_materiel.php" class="materiel-option">Lister matériel</a>
                </div>
            </div>
        </li>
    </ul>

    <ul>
        <li class="dropdown">
            <a href="#" class="dropbtn">Utilisateur</a>
            <div class="dropdown-content">
                <div class="menu-content">
                    <a href="ajouter_utilisateur.php" class="utilisateur-option">Ajouter utilisateur</a>
                    <a href="Lister_utilisateur.php" class="utilisateur-option">Lister utilisateur</a>
                </div>
            </div>
        </li>
    </ul>

    <ul>
        <li class="dropdown">
            <a href="#" class="dropbtn">Fournisseur</a>
            <div class="dropdown-content">
                <div class="menu-content">
                    <a href="ajouter_fournisseur.php" class="fournisseur-option">Ajouter fournisseur</a>
                    <a href="Lister_fournisseur2.php" class="fournisseur-option">Lister fournisseur</a>
                </div>
            </div>
        </li>
    </ul>

    <ul>
        <li class="dropdown">
            <a href="#" class="dropbtn">Affectation</a>
            <div class="dropdown-content">
                <div class="menu-content">
                    <a href="ajouter_affectation.php" class="affectation-option">Ajouter Affectation</a>
                    <a href="Lister_affectation.php" class="affectation-option">Lister Affectation</a>
                </div>
            </div>
        </li>
    </ul>

    <ul>
        <li><a href="deconnexion.php">Déconnexion</a></li>
    </ul>
</nav>
<div class="container">
    <?php
    session_start();
    if (!isset($_SESSION['utilisateur'])) {
        header('location:connexion.php');
        exit; // Assurez-vous de terminer le script ici après la redirection
    }

    // Vérifiez si les clés 'Prénom' et 'Nom' sont définies et non null avant de les afficher
    $prenom = isset($_SESSION['Prénom']) ? $_SESSION['Prénom'] : '';
    $nom = isset($_SESSION['Nom']) ? $_SESSION['Nom'] : '';

    ?>
    <h3>Bonjour : <?php echo $prenom . ' ' . $nom; ?></h3>
</div>

<script>
    // Afficher ou masquer le menu déroulant au survol de la souris
    const dropdown = document.querySelectorAll('.dropdown');
    const dropdownContent = document.querySelectorAll('.dropdown-content');
    const materielOptions = document.querySelectorAll('.materiel-option');
    const utilisateurOptions = document.querySelectorAll('.utilisateur-option');
    const fournisseurOptions = document.querySelectorAll('.fournisseur-option');

    dropdown.forEach((item, index) => {
        item.addEventListener('mouseover', () => {
            dropdownContent[index].style.display = 'flex';
        });

        item.addEventListener('mouseout', () => {
            dropdownContent[index].style.display = 'none';
        });
    });

    materielOptions.forEach(option => {
        option.addEventListener('click', (event) => {
            event.preventDefault();
            const href = option.getAttribute('href');
            window.location.href = href;
        });
    });

    utilisateurOptions.forEach(option => {
        option.addEventListener('click', (event) => {
            event.preventDefault();
            const href = option.getAttribute('href');
            window.location.href = href;
        });
    });

    fournisseurOptions.forEach(option => {
        option.addEventListener('click', (event) => {
            event.preventDefault();
            const href = option.getAttribute('href');
            window.location.href = href;
        });
    });
</script>
</body>
</html>