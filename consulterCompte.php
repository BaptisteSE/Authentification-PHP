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
    $user = getUserByName($ObjPDO, $name);
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
                    <h2>Consulter votre compte</h2>
                    <div class="connection-bienvenue">
                        <div>
                            <label>Nom :</label> 
                            <input name="name" disabled="disabled" value="<?php echo $user[0] ?>"/>
                        </div>
                        <div>
                            <label>Email :</label> 
                            <input name="email" disabled="disabled" value="<?php echo $user[1] ?>"/>
                        </div>
                    </div>
                    <a href="connection.php">Retour</a> 
                </form>
            </fieldset>   
        </section>
        <?php include_once 'footer.php';?>
        <?php }?>
    </body>        
</html>