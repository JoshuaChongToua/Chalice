
function validateForm(form_name,val_name)
{
    var x = document.forms[form_name][val_name].value;

    if (x == "") {
        alert("Login must be filled out");
        return false;
    }

}

/*function validateForm2(form_name, val_name, val_name2)
{
    var x = document.forms[form_name][val_name].value;
    var y = document.forms[form_name][val_name2].value;
    if (x == "") {
        x.style.borderColor = "red";
        alert("aaa must be filled out");
        return false;
    } else {
        x.style.borderColor = "";

    }
    if (y == "") {
        y.className = "y";
        y.style.borderColor = "red";
        alert("Password must be filled out");
        return false;
    } else {
        y.style.borderColor = "";
    }

}
*/

function validateForm2(form_name, val_name, val_name2)
{
    var x = document.forms[form_name][val_name].value;
    var y = document.forms[form_name][val_name2].value;
    if (x == "") {
        alert("Login must be filled out");
        return false;
    }
    if (y == "") {
        alert("Password must be filled out");
        return false;
    }

}

function verifierCaracteres(event) {

    var keyCode = event.which ? event.which : event.keyCode;
    var touche = String.fromCharCode(keyCode);

    var champ = document.getElementById('mon_input');

    var caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    if (caracteres.indexOf(touche) >= 0) {
        champ.value += touche;
    } else {
        alert('Les caractères spéciaux ne sont pas autorisés.');
    }
}

