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
    $lesusers = donneLesUsers($ObjPDO);
}
if (!$ok){ 
    $message='Veuillez vous authentifier pour pouvoir vous connecter.';
    echo '<script>alert("',$message,'");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=login.php">';
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]) && isset($_POST["suppression"])) {
    $idsuppression = $_POST["suppression"]; 
    deleteUser($ObjPDO, $idsuppression);
    $lesusers = donneLesUsers($ObjPDO);
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
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off">
                    <h2>Supprimer un utilisateur</h2>
                    <div class="connection-mon-compte">
                         <table>
                              <thead>
                                 <tr>
                                    <td colspan="5"><h3>Liste des utilisateurs existants</h3></td>  
                                </tr>
                             </thead>
                             <tr>
                                 <th>Sélection</th>
                                 <th>Id</th>
                                 <th>Nom</th>
                                 <th>Email</th>
                                 <th>Type utilisateur</th>
                             </tr> 
                            <?php foreach($lesusers as $unuser){ ?>   
                             <tr>
                                 <td><input type="radio" name="suppression" value="<?php echo $unuser[0] ?>"/></td>
                                 <td><?php echo $unuser[0] ?></td> 
                                 <td><?php echo $unuser[1] ?></td> 
                                 <td><?php echo $unuser[2] ?></td> 
                                 <td><?php echo $unuser[3] ?></td>
                             </tr>
                            <?php } ?> 
                         </table>
                    </div>
                    <input type="submit" name="submit" value="SUPPRIMER"/>
                    <a href="connection.php">Retour</a> 
                </form>
            </fieldset>   
        </section>
        <?php include_once 'footer.php';?>
        <?php }?>
    </body>        
</html>