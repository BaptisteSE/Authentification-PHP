<!DOCTYPE html>
<?php
    session_start();
    session_unset();
    session_destroy();
    include_once 'mesFonctions.php';
    
    # Affichage d'un message en retour du changement de mot de passe
    if (isset($_GET["error"])) {
        if(!empty($_GET['error'])) {
            $error = $_GET["error"];
        }
    }

    #secho '<script>alert("',$_POST["name"],'");</script>';  
    // Validation du formulaire
            
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]) && isset($_POST["name"]) && isset($_POST["password"])) {
   
        $name = $_POST["name"]; 
        $password = $_POST["password"];
        
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
            #echo '<script>alert("decode success");</script>';
            
            if(checkUser($ObjPDO, $name, $password)==true){
                #$errs["form"][] = "Vous êtes connecté !";
                #echo '<script>alert("Vous etes connecte");</script>';
                $error = "";
                // on crée une session
                session_start();
                // on passe les paramètre à la session
                $_SESSION['name'] = $name;
                $_SESSION['password'] = $password;
                header("Location: connection.php");
                die();
            }else {
                $error = "Compte inexistant ou non validé, inscrivez-vous si nécessaire, recommencez une saisie ou validez votre email de demande d'inscription !";
            }
	}
	else {
            #$message = "Vous n'avez pas pu vous connecter, le code de vérification est incorrecte";
            #echo '<script>alert("',$message,'");</script>';
            $error = "Vous n'avez pas pu vous connecter, le code de vérification est incorrecte";
	}
    }	
?>
<html>
    <head>
        <title>Le site de SERET Baptiste - BTS SIO 1ère année</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://www.google.com/recaptcha/api.js"></script>   
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/style2.css">
    </head>
    <body>
        <?php include_once 'header.php';?>
        <section id="section-login">
            
            <fieldset>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off">
                    <p>
                        <label for="name">Nom d'utilisateur</label>
                        <input id="name" name="name" type="text" placeholder="Entrer votre nom d'utilisateur" required value="">
                    </p>
                    <p>
                        <label for="password">Mot de passe</label>
                        <input id="password" name="password" type="password" placeholder="Entrer votre Mot de passe" pattern=".{8,}" required value="">
                    </p>	
                    <div class="g-recaptcha"
                    data-sitekey="6LeEqOMUAAAAAHRJ0dpgPtvQZnYHkmbT3zSkGgH7" >
                    </div>
                    <div class="login-erreurs">
                     <?php if(isset($error)) { echo '<span style="color:red">'.$error.'</span>'; } ?>
                    </div>
                    <input type="submit" name="submit" value="SE CONNECTER"/>
                    <div id="autres-liens">
                        <a href="inscription.php">Pas encore inscrit ?</a>
                        <a href="demandeNewPassword.php">Mot de passe oublié</a>
                    </div>

                </form>
                
            </fieldset>
            
        </section> 
        
         
        <?php include_once 'footer.php';?>
    </body>
</html>

