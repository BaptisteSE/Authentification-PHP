<?php

include_once 'mesFonctions.php';
$error = "" ;

# quand on arrive la première fois sur la gpage, il s'agit du token et email passé dans l'URL
if (isset($_GET["token"]) && isset($_GET["email"])) {
    if(!empty($_GET['token']) && !empty($_GET['email'])) {
        $token = $_GET["token"];
        $email = $_GET["email"];
    }
}

# une fois le formulaire validé
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]) 
        && isset($_POST["token"]) && isset($_POST["email"]) 
        && isset($_POST["passwordConf"]) && isset($_POST["password"])) {
   
    if(!empty($_POST['password']) && !empty($_POST['passwordConf'])){
        $password = $_POST["password"];
        $passwordConf = $_POST["passwordConf"];
        $token = $_POST["token"];
        $email = $_POST["email"];
        
        if ($password != $passwordConf) {
            $message = 'Mots de passe non identiques';
            echo '<script>alert("',$message,'");</script>';
        }
        else {
            
            // Ma clé privée
            $secret = "6LeEqOMUAAAAAIzJ7U0H_Xp9-gfg3ovWmyDSXYJr";
            // Paramètre renvoyé par le recaptcha
            $response = $_POST['g-recaptcha-response'];
            // On récupère l'IP de l'utilisateur
            $remoteip = $_SERVER['REMOTE_ADDR'];

            $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=" 
                . $secret
                . "&response=" . $response
                . "&remoteip=" . $remoteip ;

            $decode = json_decode(file_get_contents($api_url), true); 
            if ($decode['success'] == true) {
                $ObjPDO = connexion();

                if (!checkTokenAndEmail($ObjPDO, $email, $token)) {
                    updatePasswordByTokenEmail($ObjPDO, $token, $email, $password);
                    $error = "Votre mot de passe a été modifié, reconnectez-vous avec votre nouveau mot de passe !";

                    header("Location: login.php?error=$error");
                    die();
                } else {
                    $error = "Url de changement de mot de passe plus valide, refaire une demande de changement de mot de passe !";
                }
            }
            else {
                $error = "Mise à jour abandonnée, le code de vérification est incorrecte";
            }
        }
    }
}
    
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Le site de SERET Baptiste - BTS SIO 1ère année</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://www.google.com/recaptcha/api.js"></script>   
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/style2.css">
        <script src="js/control_password.js"></script>
    </head>
    <body>
        <?php include_once 'header.php';?>
        <section id="section-login">
            
            <fieldset>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off">
                    <h2>Changement de mot de passe </h2>
                    <p>
                        <label for="password">Nouveau mot de passe (8 caractères minimum)</label><span id="passwordError"></span>
                        <input type="password" name="password" id="password" placeholder="Entrer votre nouveau mot de passe" pattern=".{8,}" required onblur="change_password();"> 
                        <input type="hidden" id="token" name="token" value="<?php echo $token ?>"/>
                        <input type="hidden" id="email" name="email" value="<?php echo $email ?>"/>
                    </p>
                    <p>
                        <label for="passwordConf">Nouveau mot de passe</label>
                        <input type="password" name="passwordConf" id="passwordConf" placeholder="Confirmer le nouveau mot de passe" pattern=".{8,}" required onblur="change_password();">
                    </p>
                    <?php if(isset($error)) { echo '<span style="color:red">'.$error.'</span>'; } ?>
                    
                    <div class="g-recaptcha"
                    data-sitekey="6LeEqOMUAAAAAHRJ0dpgPtvQZnYHkmbT3zSkGgH7" >
                    </div>
                    
                    <input type="submit" name="submit" value="VALIDER"/>                    

                </form>
            </fieldset> 
        </section> 
        <?php include_once 'footer.php';?>
    </body>        
</html>