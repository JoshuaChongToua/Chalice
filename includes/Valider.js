function validateForm()
{
    var x = document.forms["typeForm"]["role"].value;
    if (x == "") {
        alert("Name must be filled out");
        return false;
    }
}