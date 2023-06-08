<script src="../assets/js/jquery-3.6.0.min.js"></script>
<script src="https://cdn.tiny.cloud/1/7z35pqy407ei7ctvi0ioouusk8zni4ikprha2ndun8v5qign/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
<script src="../assets/js/tinymce-jquery.min.js"></script>

<script src="../assets/js/lib/jquery.nanoscroller.min.js"></script>
<script src="../assets/js/lib/menubar/sidebar.js"></script>
<script src="../assets/js/lib/preloader/pace.min.js"></script>
<script src="../assets/js/lib/bootstrap.min.js"></script>


<script src="../assets/js/lib/select2/select2.full.min.js"></script>
<script src="../assets/js/lib/form-validation/jquery.validate.min.js"></script>
<script src="../assets/js/lib/form-validation/jquery.validate-init.js"></script>

<script src="../assets/js/lib/jsgrid/db.js"></script>
<script src="../assets/js/lib/jsgrid/jsgrid.core.js"></script>
<script src="../assets/js/lib/jsgrid/jsgrid.load-indicator.js"></script>
<script src="../assets/js/lib/jsgrid/jsgrid.load-strategies.js"></script>
<script src="../assets/js/lib/jsgrid/jsgrid.sort-strategies.js"></script>
<script src="../assets/js/lib/jsgrid/jsgrid.field.js"></script>
<script src="../assets/js/lib/jsgrid/fields/jsgrid.field.text.js"></script>
<script src="../assets/js/lib/jsgrid/fields/jsgrid.field.number.js"></script>
<script src="../assets/js/lib/jsgrid/fields/jsgrid.field.select.js"></script>
<script src="../assets/js/lib/jsgrid/fields/jsgrid.field.checkbox.js"></script>
<script src="../assets/js/lib/jsgrid/fields/jsgrid.field.control.js"></script>

<script src="../assets/js/scripts.js"></script>

<script>
    $(document).ready(function () {
        var setDateDefault = function () {
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

            document.getElementById('date').value = year + '-' + month + '-' + day;
        };

        var getImageSelect = function (string)
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
        };
        var getImageProfileSelect = function (string)
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
        };


        var validateForm = function (form)
        {
            var x = document.forms[form];

            if (x.value === "") {

                $(form).addClass("has-error");
                alert("login must be filled out");
                return false;
            } else {
                $(form).removeClass("has-error");
            }

        };

        var validateForm2 = function (form_name, val_name, val_name2)
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

        };


        var verifierCaracteres = function (event) {

            var keyCode = event.which ? event.which : event.keyCode;
            var touche = String.fromCharCode(keyCode);

            var champ = document.getElementById('mon_input');

            var caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

            if (caracteres.indexOf(touche) >= 0) {
                champ.value += touche;
            } else {
                alert('Les caractères spéciaux ne sont pas autorisés.');
            }
        };


        var setDate = function ()
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
        };

        var validateEmail = function ()
        {
            var emailInput = document.getElementById("email");
            var email = emailInput.value;
            var pattern = /^[\w\.-]+@[\w\.-]+\.\w+$/;

            if (pattern.test(email) == false) {
                // L'e-mail est invalide, afficher un message d'erreur
                alert("L'adresse e-mail est invalide.");
                return false;
            }
        };

        $('textarea#tiny').tinymce({
            height: 500,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | removeformat | help'
        });

        if ($("#date").length > 0) {
            setDateDefault();
        }

        setDate();
    });

</script>

</body>


</html>