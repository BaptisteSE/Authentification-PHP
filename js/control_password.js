/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function change_password()
{ 
    pass = document.getElementById('password').value;
    cf_pass = document.getElementById('passwordConf').value;
    error = document.getElementById('passwordError');
    if (pass && cf_pass && pass != cf_pass) {   
        error.innerHTML = "Attention, mots de passe non identiques";
    }
    else {
        error.innerHTML = "";
    }
}

