function getImageSelect(string)
{
    if (string == ""){
        document.getElementById("test").innerHTML = "";

    } else {
        var imgElement = document.createElement("img");
        var imgPath = "../assets/images/" + string + ".jpg" ;
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
        var imgPath = "../assets/images/" + image + ".jpg" ;
        imgElement.src = imgPath;
        document.getElementById("test").innerHTML = "";
        document.getElementById("test").appendChild(imgElement);
    }
}