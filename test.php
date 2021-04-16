<?php

include_once 'mesFonctions.php';
$ObjPDO = connexion();
var_dump(lesTypesAdmin($ObjPDO));
