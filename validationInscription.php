<?php

include_once 'mesFonctions.php';
$error = "" ;
$token = "";
$email = "";

# quand on arrive la première fois sur la page, il s'agit du token et email passé dans l'URL
if (isset($_GET["token"]) && isset($_GET["email"])) {
    if(!empty($_GET['token']) && !empty($_GET['email'])) {
        $token = $_GET["token"];
        $email = $_GET["email"]; 
    }
}

# quand on valide le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && isset($_POST['email']) && isset($_POST['token'])){
    if(!empty($_POST['email']) && !empty($_POST['token'])){
        $ObjPDO = connexion();
        # raz du token d'inscription
        $email = $_POST['email'];
        updateTokenInscriptionNull($ObjPDO, $_POST['token'], $email);
        # affichage de la page de bienvenue sur l'espace client
        $name = getNameByEmail($ObjPDO, $email);
        if (!empty($name)) {           
            header("Location: connection.php?");
            die();
        }
    }
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
        <?php include_once 'header.php';?>
        <section id="section-connection">
             <fieldset>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off">
                    <h2>Validation de votre demande d'inscription</h2>
                    <div class="connection-bienvenue">
                        <h1>Veuillez valider votre demande en cliquant sur le bouton ci-dessous :</h1>
                    </div>
                    <input type="hidden" id="token" name="token" value="<?php echo $token ?>"/>
                    <input type="hidden" id="email" name="email" value="<?php echo $email ?>"/>
                    <input type="submit" name="submit" value="VALIDER"/>
                </form>
            </fieldset>
        </section>
        <?php include_once 'footer.php';?>
        </body>        
</html>