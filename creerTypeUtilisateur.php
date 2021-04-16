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
    $lestypes = donneLesTypes($ObjPDO);
}
if (!$ok){ 
    $message='Veuillez vous authentifier pour pouvoir vous connecter.';
    echo '<script>alert("',$message,'");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=login.php">';
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]) && isset($_POST["idtypeNew"]) && isset($_POST["descriptiontypeNew"])) {
    $idtypeNew = $_POST["idtypeNew"];
    $descriptiontypeNew = $_POST["descriptiontypeNew"];
    $admintypeNew = false;
    if (isset($_POST["admintypeNew"])) {
        $admintypeNew = true;
    }
    if (!checkTypeExiste($ObjPDO, $idtypeNew)) {
        creerType($ObjPDO, $idtypeNew, $descriptiontypeNew, $admintypeNew);
        $lestypes= donneLesTypes($ObjPDO); 
    }else {
        $message='Id déjà existant !';
        echo '<script>alert("',$message,'");</script>';
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
        <?php if($ok) {?>
        <?php include_once 'header.php';?>    
        <section id="section-connection">
            <fieldset>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off">
                    <h2>Créer un nouveau type d'utilisateur</h2>
                    <div class="connection-mon-compte">
                         <table>
                             <thead>
                                 <tr>
                                    <td colspan="3"><h3>Liste des types existants</h3></td>  
                                </tr>
                             </thead>
                             <tr> 
                                 <th>Id</th>
                                 <th>Description</th>
                                 <th>Administrateur</th> 
                             </tr> 
                            <?php foreach($lestypes as $untype){ ?>   
                             <tr>
                                 <td><?php echo $untype[0] ?></td> 
                                 <td><?php echo $untype[1] ?></td> 
                                 <td><input type="checkbox" name="admintypeNew" disabled="disabled" <?php if ($untype[2]==1) {?> checked="checked" <?php } ?>/></td>
                             </tr>
                            <?php } ?>
                             <tfoot>
                                <tr>
                                    <td colspan="3"><h3>Nouveau type</h3></td>  
                                </tr>
                                <tr> 
                                    <th>Id</th>
                                    <th>Description</th>
                                    <th>Administrateur</th> 
                                </tr> 
                                <tr>
                                    <td>
                                        <input type="text" name="idtypeNew" size="3" maxlength="3" required=""/>
                                    </td>
                                    <td>
                                        <input type="text" name="descriptiontypeNew" size="80" maxlength="80" required=""/>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="admintypeNew"/>
                                    </td> 
                                </tr>
                             </tfoot>
                         </table>
                    </div>
                    <input type="submit" name="submit" value="AJOUTER"/>
                    <a href="connection.php">Retour</a> 
                </form>
            </fieldset>   
        </section>
        <?php include_once 'footer.php';?>
        <?php }?>
    </body>        
</html>