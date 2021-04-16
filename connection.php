<?php
// on crée une session
session_start();
include_once 'mesFonctions.php';
$ObjPDO = connexion();
$ok = false;
$isAdmin = false;
if (isset($_SESSION['name']) && isset($_SESSION['password']) && !empty($_SESSION['name']) && !empty($_SESSION['password'])) {
    $ok = true;
    $name = $_SESSION['name'];
    $isAdmin = isAdmin($ObjPDO, $name);
}
if (!$ok){ 
    $message='Veuillez vous authentifier pour pouvoir vous connecter.';
    echo '<script>alert("',$message,'");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=login.php">';
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Espace</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/style2.css">
    </head>
    <body>
        <?php if($ok) {?>
        <?php include_once 'header.php';?>    
        <section id="section-connection">
            
            <fieldset>
                <form>
                    <h2>Votre espace client</h2>
                    <div class="connection-bienvenue">
                        <h1>Bienvenue Agent : <?php echo $_SESSION['name']; ?></h1>
                    </div>
                    <?php if($isAdmin) { ?>
                    <div class="">                        
                        <a href="creerTypeUtilisateur.php">Créer un nouveau type d'utilisateur</a>
                        <a href="supprimerUtilisateur.php">Supprimer un utilisateur</a>

                    </div>
                    <?php }else{ ?>
                        <a href="consulterCompte.php">Consulter mon compte</a>
                        <a href="modifierCompte.php">Modifier mon compte</a>
                    <?php }?>
                </form>
            </fieldset>   
        </section>
        <?php include_once 'footer.php';?>
        <?php }?>
    </body>        
</html>