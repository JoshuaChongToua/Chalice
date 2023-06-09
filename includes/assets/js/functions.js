function getImageSelect (string)
{
    if (string == "--"){
        document.getElementById("test").innerHTML = "";

    } else {

            var imgElement = document.createElement("img");
            var imgPath = "../assets/images/upload/" + string + ".jpg";
            imgElement.src = imgPath;
            document.getElementById("test").innerHTML = "";
            document.getElementById("test").appendChild(imgElement);

    }

}
function getImageProfileSelect (string)
{
    if (string == ""){
        document.getElementById("test").innerHTML = "";

    } else {
        var imgElement = document.createElement("img");
        const id = document.getElementById("user_id").value;
        var imgPath = "../assets/images/profiles/"+ id + "/" + string + ".jpg" ;
        imgElement.src = imgPath;
        console.log("Chemin de l'image : " + imgElement.src);

        document.getElementById("test").innerHTML = "";
        document.getElementById("test").appendChild(imgElement);
    }
}
