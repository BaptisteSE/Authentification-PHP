<?php

function connexion() {
    try {
        $ObjPDO = new PDO('mysql:dbname=authentificationtpa3bs;host=localhost;charset=utf8', 'root', 'root');
        return $ObjPDO;
    } catch (PDOExeception $e) {
        echo 'Connexion échouée';
    }
}
function addUser(PDO $ObjPDO, $name, $email, $password){
    $repPrep = $ObjPDO->prepare('INSERT INTO users VALUES(NULL,:name,:email,:password,:tokenInscription,:tokenPassword,2)');
    $bvc1 = $repPrep->bindValue(':name',$name,PDO::PARAM_STR);
    $bvc2 = $repPrep->bindValue(':email',$email,PDO::PARAM_STR);
    $bvc3 = $repPrep->bindValue(':password',password_hash($password, PASSWORD_DEFAULT),PDO::PARAM_STR);
    $bvc4 = $repPrep->bindValue(':tokenInscription',generateRandomHex(),PDO::PARAM_STR);
    $bvc4 = $repPrep->bindValue(':tokenPassword',generateRandomHex(),PDO::PARAM_STR);
    $okExecution = $repPrep->execute();
    return $repPrep;
}
function updateUser(PDO $ObjPDO, $nameNew, $nameOld, $emailNew, $emailOld){
    $repPrep = $ObjPDO->prepare('UPDATE users set name=:nameNew, email=:emailNew where name =:nameOld and email=:emailOld');
    $repPrep->bindValue(':nameNew',$nameNew,PDO::PARAM_STR);
    $repPrep->bindValue(':emailNew',$emailNew,PDO::PARAM_STR);
    $repPrep->bindValue(':nameOld',$nameOld,PDO::PARAM_STR);
    $repPrep->bindValue(':emailOld',$emailOld,PDO::PARAM_STR);
    $repPrep->execute();
    return $repPrep;
}
function deleteUser(PDO $ObjPDO, $id){
    $repPrep = $ObjPDO->prepare('DELETE FROM users where id=:id');
    $repPrep->bindValue(':id',$id,PDO::PARAM_INT); 
    $repPrep->execute();
    return $repPrep;
}
function donneLesUsers(PDO $ObjPDO){
    $repPrep = $ObjPDO->prepare("SELECT id, name, email, t.descriptiontype as descriptiontype FROM users u inner join typeutilisateur t on u.idtype=t.idtype where t.admintype <> 1");
    $repPrep->execute();
    $lesusers = $repPrep->fetchAll();
    return $lesusers;
}
function donneLesTypes(PDO $ObjPDO){
    $repPrep = $ObjPDO->prepare("SELECT * FROM typeutilisateur");
    $repPrep->execute();
    $lestypes = $repPrep->fetchAll();
    return $lestypes;
}
function creerType(PDO $ObjPDO, $idtype, $descriptiontype, $admintype){
    $repPrep = $ObjPDO->prepare('INSERT INTO typeutilisateur VALUES(:idtype,:descriptiontype,:admintype)');
    $repPrep->bindValue(':idtype',$idtype,PDO::PARAM_INT);
    $repPrep->bindValue(':descriptiontype',$descriptiontype,PDO::PARAM_STR);
    $repPrep->bindValue(':admintype', $admintype,PDO::PARAM_BOOL); 
    $repPrep->execute();
    return $repPrep;
}
function checkTypeExiste(PDO $ObjPDO, $idtype) {
    $boolean = false;
    $repPrep = $ObjPDO->prepare('select * from typeutilisateur where idtype=:idtype');
    $repPrep->bindValue(':idtype',$idtype,PDO::PARAM_INT); 
    $repPrep->execute();
    $uneLigne = $repPrep->fetchAll();
    if (count($uneLigne) >= 1){
        $boolean = true;
    }
    return $boolean; 
}
function checkUser(PDO $ObjPDO, $name, $password){
    $check = false;
    $repPrep = $ObjPDO->prepare('SELECT email,mdp FROM users WHERE name=:name and tokenInscription is NULL');
    $bvc1 = $repPrep->bindValue(':name',$name,PDO::PARAM_STR);
    $okExecution = $repPrep->execute();
    $uneLigne = $repPrep->fetchAll();
    return checkPWD($password,$uneLigne);
}
function checkPWD($password,$pwdBDD){
    $check = false;
    if (count($pwdBDD) == 1) {
        $check = password_verify($password, $pwdBDD[0]["mdp"]);
    }
    return $check;
}
function checkEmail(PDO $ObjPDO, $email){
    $boolean = true;
    $repPrep = $ObjPDO->prepare('SELECT email FROM users WHERE email=:email');
    $bvc1 = $repPrep->bindValue(':email',$email,PDO::PARAM_STR);
    $excute = $repPrep->execute();
    $uneLigne = $repPrep->fetchAll();
    if (count($uneLigne) >= 1){
        $boolean = false;
    }
    return $boolean;
}
function checkName(PDO $ObjPDO, $name){
    $boolean = true;
    $repPrep = $ObjPDO->prepare('SELECT name,mdp FROM users WHERE name=:name');
    $bvc1 = $repPrep->bindValue(':name',$name,PDO::PARAM_STR);
    $excute = $repPrep->execute();
    $uneLigne = $repPrep->fetchAll();
    if (count($uneLigne) >= 1){
        $boolean = false;
    }
    return $boolean;
}
function checkUserExiste(PDO $ObjPDO, $nameOld, $emailOld, $nameNew, $emailNew) {
    $boolean = false;
    $repPrep = $ObjPDO->prepare('select * from users u1 where u1.id not in (SELECT id FROM users where name=:nameOld and email=:emailOld) and (u1.name=:nameNew or email=:emailNew)');
    $repPrep->bindValue(':nameOld',$nameOld,PDO::PARAM_STR);
    $repPrep->bindValue(':emailOld',$emailOld,PDO::PARAM_STR);
    $repPrep->bindValue(':nameNew',$nameNew,PDO::PARAM_STR);
    $repPrep->bindValue(':emailNew',$emailNew,PDO::PARAM_STR);
    $repPrep->execute();
    $uneLigne = $repPrep->fetchAll();
    if (count($uneLigne) >= 1){
        $boolean = true;
    }
    return $boolean; 
}
function getTokenByEmail(PDO $ObjPDO, $email){
    $repPrep = $ObjPDO->prepare('SELECT tokenPassword FROM users WHERE email=:email');
    $bvc1 = $repPrep->bindValue(':email',$email,PDO::PARAM_STR);
    $excute = $repPrep->execute();
    $uneLigne = $repPrep->fetch();
    return $uneLigne[0];
}
function getTokenInscriptionByEmail(PDO $ObjPDO, $email){
    $repPrep = $ObjPDO->prepare('SELECT tokenInscription FROM users WHERE email=:email');
    $bvc1 = $repPrep->bindValue(':email',$email,PDO::PARAM_STR);
    $excute = $repPrep->execute();
    $uneLigne = $repPrep->fetch();
    return $uneLigne[0];
}
function getNameByEmail(PDO $ObjPDO, $email){
    $repPrep = $ObjPDO->prepare('SELECT name FROM users WHERE email=:email');
    $bvc1 = $repPrep->bindValue(':email',$email,PDO::PARAM_STR);
    $excute = $repPrep->execute();
    $uneLigne = $repPrep->fetch();
    return $uneLigne[0];
}
function getUserByName(PDO $ObjPDO, $name){
    $repPrep = $ObjPDO->prepare('SELECT name, email FROM users WHERE name=:name');
    $bvc1 = $repPrep->bindValue(':name',$name,PDO::PARAM_STR);
    $excute = $repPrep->execute();
    $uneLigne = $repPrep->fetch();
    return $uneLigne;
}
function checkTokenAndEmail(PDO $ObjPDO, $email, $tokenPassword){
    $boolean = true;
    $repPrep = $ObjPDO->prepare('SELECT tokenPassword FROM users WHERE tokenPassword=:tokenPassword and email=:email');
    $bvc1 = $repPrep->bindValue(':tokenPassword',$tokenPassword,PDO::PARAM_STR);
    $bvc1= $repPrep->bindValue(':email',$email,PDO::PARAM_STR);
    $excute = $repPrep->execute();
    $uneLigne = $repPrep->fetchAll();
    if (count($uneLigne) >= 1){
        $boolean = false;
    }
    return $boolean;
}
function updatePasswordByTokenEmail(PDO $ObjPDO, $tokenPassword, $email, $password) {
    $r = $ObjPDO->prepare('UPDATE users SET mdp=:password, tokenPassword=:tokenNew WHERE tokenPassword=:tokenPassword and email=:email');
    $bvc1=$r->bindValue(':password',password_hash($password, PASSWORD_DEFAULT),PDO::PARAM_STR);
    $bvc1=$r->bindValue(':tokenPassword',$tokenPassword,PDO::PARAM_STR);
    $bvc1=$r->bindValue(':email',$email,PDO::PARAM_STR);
    $bvc1=$r->bindValue(':tokenNew',generateRandomHex(),PDO::PARAM_STR);
    $okExecution=$r->execute();
    return $r;
}
function updateTokenInscriptionNull(PDO $ObjPDO, $tokenInscription, $email) {
    $r = $ObjPDO->prepare('UPDATE users SET tokenInscription=:tokenNull WHERE tokenInscription=:tokenInscription and email=:email'); 
    $bvc1=$r->bindValue(':tokenInscription',$tokenInscription,PDO::PARAM_STR);
    $bvc1=$r->bindValue(':email',$email,PDO::PARAM_STR);
    $bvc1=$r->bindValue(':tokenNull',NULL,PDO::PARAM_STR);
    $okExecution=$r->execute();
    return $r;
}
function generateRandomHex() {
    // Generate a 32 digits hexadecimal number
    $numbytes = 16; // Because 32 digits hexadecimal = 16 bytes
    $bytes = openssl_random_pseudo_bytes($numbytes);
    $hex = bin2hex($bytes);
    return $hex;
}
function isAdmin(PDO $ObjPDO, $name){
    $admin = false;
    $repPrep = $ObjPDO->prepare('SELECT * FROM users u INNER JOIN typeutilisateur t ON t.idtype = u.idtype WHERE name=:name and t.admintype = 1');
    $bvc1=$repPrep->bindValue(':name',$name,PDO::PARAM_STR);
    $excute = $repPrep->execute();
    $uneLigne = $repPrep->fetchAll();
    if (count($uneLigne) >= 1){
        $admin = true;
    }
    return $admin;
}

