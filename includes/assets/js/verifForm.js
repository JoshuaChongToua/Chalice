
function validateForm(form_name,val_name)
{
    var x = document.forms[form_name][val_name].value;

    if (x == "") {
        alert("Login must be filled out");
        return false;
    }

}

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