<?php
session_start();
session_unset();
session_destroy();
include_once 'mesFonctions.php';
$errs = array(); 

 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]) 
        && isset($_POST["email"]) && isset($_POST["password"]) 
        && isset($_POST["passwordConf"]) && isset($_POST["name"])) 
{
   
        $email = $_POST["email"];
        $name = $_POST["name"];
        $password = $_POST["password"];
        $passwordConf = $_POST["passwordConf"];
        
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

                if (checkEmail($ObjPDO, $email) == true) {
                    if (checkName($ObjPDO, $name)== true) {
                        addUser($ObjPDO,$name,$email,$password);
                        $message = "Votre demande d'inscription est enregistrée, veuillez valider votre email pour finaliser l'inscription !";
                        echo '<script>alert("',$message,'");</script>';
                        $tokenInscription = getTokenInscriptionByEmail($ObjPDO, $email);
                        header("Location: validationInscription.php?token=$tokenInscription&email=$email");
                        die();
                    }
                    else {
                        $message = 'Erreur - Ce nom existe déjà, inscription impossible !';
                        echo '<script>alert("',$message,'");</script>';
                    }       
                }
                else {
                        $message = 'Erreur - Cet email existe déjà, inscription impossible !';
                        echo '<script>alert("',$message,'");</script>';
                }
            }
            else {
                #$message = "Vous n'avez pas pu vous connecter, le code de vérification est incorrecte";
                #echo '<script>alert("',$message,'");</script>';
                $errs["form"][] = "Vous n'avez pas pu vous connecter, le code de vérification est incorrecte";
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
                    <!--<div id="accueil">
                        <a href="index2.php"><img alt="" id="img-accueil" src="img/accueil.png"/></a>
                        <a href="index2.php"><h2>Accueil</h2></a>
                    </div>-->
                    <p>
                        <label for="name">Nom d'utilisateur</label>
                        <input id="name" name="name" type="text" placeholder="Entrer votre nom d'utilisateur" required value="">
                    </p>
                    <p>
                        <label for="email">Email</label>
                        <input id="Email" name="email" type="email" placeholder="Entrer votre email" required value="" >
                    </p>
                    <p>
                        <label for="password">Mot de passe (8 caractères minimum)</label><span id="passwordError"></span>
                        <input type="password" name="password" id="password" placeholder="Entrer votre Mot de passe" pattern=".{8,}" required onblur="change_password();"> 
                    </p>
                    <p>
                        <label for="passwordConf">Mot de passe</label>
                        <input type="password" name="passwordConf" id="passwordConf" placeholder="Confirmer le mot de passe" pattern=".{8,}" required onblur="change_password();">
                    </p>
                    <div class="g-recaptcha"
                    data-sitekey="6LeEqOMUAAAAAHRJ0dpgPtvQZnYHkmbT3zSkGgH7" >
                    </div>
                    
                    <input type="submit" name="submit" value="S'INSCRIRE"/>                    

                </form>
                
            </fieldset>
            
        </section> 