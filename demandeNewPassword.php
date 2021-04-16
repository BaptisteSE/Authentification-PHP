<!DOCTYPE html>
<?php

include_once 'mesFonctions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && isset($_POST['email'])){
    if(!empty($_POST['email'])){
        $ObjPDO = connexion();
        # erreur si email déjà existant
        if (checkEmail($ObjPDO, $_POST['email']) == true) {
            $error = "Erreur - Cet email n'existe pas !";
            #echo '<script>alert("',$error,'");</script>';
        }else{
            //Génerer un token unique et le stocker dans la base de données
            //Créer le message a envoyer par mail avec un lien menant vers la page permettant la modification de mot de passe
            //Envoyer le mail
            //Rediriger vers une page confirmant l'envoie d'email
            $ObjPDO = connexion();
            $email = $_POST['email'];
            $token = getTokenByEmail($ObjPDO,$email);
            if ($token == null) {
                $error = "Absence de token, erreur";
            }else{
                // TODO
                //Envoie un mail de confirmation pour changement de mot de passe
                #$to = $email;
                #$sujet = 'Mot de passe perdu';
                #$body = 'Bonjour, veuillez cliquer sur le lien ci-dessous pour changer votre mot de passe -> <a href="newPassword.php?token='.$token.'&email='.$email.'">Changement de mot de passe</a>';
                #$message = wordwrap($body, 70, "\r\n");
                #$headers = 'From: webmaster@example.com' . "\r\n" .
                #     'Reply-To: webmaster@example.com' . "\r\n" .
                #     'X-Mailer: PHP/' . phpversion();
                #
                #mail($to, $sujet, $message, $headers);
                
                # SOLUTION DE REMPLACEMENT
                header("Location: validationChangementMotdepasse.php?token=$token&email=$email");
                die(); 
            }
        }
    }else{
        $error = "Veuillez entrer votre adresse mail";
    }
}

?>
<html lang="fr">
    <!-- Exemple URL dans le mail -->
    <!-- http://localhost/TPA03BS/newPassword.php?token=3bcc5b22a0a808647d763e39adbed8ab&email=user2@mail.com n'existe plus-->
    <!-- http://localhost/TPA03BS/newPassword.php?token=9ca143adbfe8a3c9ae69a0aac25a5b11&email=user5@mail.com -->
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
                    <h2>Demande de changement de mot de passe</h2>
                    <p>
                        <label for="email">Email</label>
                        <input id="Email" name="email" type="email" placeholder="Entrer votre adresse mail" >
                        <?php if(isset($error)) { echo '<span style="color:red">'.$error.'</span>'; } ?>
                    </p>
                    
                    <input type="submit" name="submit" value="VALIDER"/> 
                </form>          
            </fieldset>
        <?php include_once 'footer.php';?>
    </body>
</html>