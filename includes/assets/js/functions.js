function getImageSelect(string)
{
    if (string == ""){
        document.getElementById("test").innerHTML = "";

    } else {
        var imgElement = document.createElement("img");
        var imgPath = "../assets/images/upload/" + string + ".jpg" ;
        imgElement.src = imgPath;
        document.getElementById("test").innerHTML = "";
        document.getElementById("test").appendChild(imgElement);
    }
}function getImageSelect2(string)
{
    if (string == ""){
        document.getElementById("test").innerHTML = "";

    } else {
        var imgElement = document.createElement("img");
        const image = document.getElementById("image_id")
        var imgPath = "../assets/images/upload/" + image + ".jpg" ;
        imgElement.src = imgPath;
        document.getElementById("test").innerHTML = "";
        document.getElementById("test").appendChild(imgElement);
    }
}


function validateForm(form_name,val_name)
{
    var x = document.forms[form_name][val_name];

    if (x.value === "") {
        x.style.borderColor = "red";
        alert("aaa must be filled out");
        return false;
    } else {
        x.style.borderColor = "";


    }

}

function validateForm2(form_name, val_name, val_name2)
{

    var x = document.forms[form_name][val_name];

    var y = document.forms[form_name][val_name2];
    if (x.value === "") {
        x.style.borderColor = "red";
        alert("aaa must be filled out");
        return false;

    } else {
        x.style.borderColor = "";

    }

    if (y.value === "") {
        y.style.borderColor = "red";
        alert("Password must be filled out");
        return false;
    } else {
        y.style.borderColor = "";
    }

}


/*function validateForm2(form_name, val_name, val_name2)
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

 */

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


function setDate()
{
    var today = new Date();
    var day = today.getDate();
    var month = today.getMonth() + 1;
    var year = today.getFullYear();

    if (day < 10) {
        day = '0' + day;
    }

    if (month < 10) {
        month = '0' + month;
    }

    var formattedDate = year + '-' + month + '-' + day;
    document.getElementById('date').setAttribute('min', formattedDate);
}



