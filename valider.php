<?php
    include_once 'mesFonctions.php';

        // Validation du formulaire
    if (strlen($_POST["submit"]) > 0) {
    
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
	if ($decode['success'] == true && count($errs) == 0) {
            $ObjPDO = connexion();
            $email = $_POST['Email'];
            $Password = $_POST['password'];
            if(checkUser($ObjPDO, $email, $Password)==true){
                $message = "Vous êtes connecté !";
                echo '<script>alert("',$message,'");</script>';
                header("Location: login.php");
                die();
            }
	}
	else {
            $errs["form"][] = "La validation du formulaire n'est pas implementee.";
            $messagebot = "C'est un robot ou le code de vérification est incorrecte";            
            $message2 = "Vous n'avez pas pu vous connecter, le code de vérification est incorrecte";
            echo '<script>alert("',$message2,'");</script>';  
	}
    }	
?>
