<script src="../assets/js/jquery-3.6.0.min.js"></script>
<script src="https://cdn.tiny.cloud/1/7z35pqy407ei7ctvi0ioouusk8zni4ikprha2ndun8v5qign/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="../assets/js/tinymce-jquery.min.js"></script>

<script src="../assets/js/lib/jquery.nanoscroller.min.js"></script>
<script src="../assets/js/lib/menubar/sidebar.js"></script>
<script src="../assets/js/lib/preloader/pace.min.js"></script>
<script src="../assets/js/lib/bootstrap.min.js"></script>
<script src="../assets/js/scripts.js"></script>


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

<script>
    function setDateDefault()
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

        document.getElementById('date').value = year + '-' + month + '-' + day;
    }
    $(document).ready(function () {
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
    });
</script>

</body>


</html>