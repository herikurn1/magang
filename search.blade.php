<!DOCTYPE html>
<html>
    <head>
        <title>CK Editor</title>
        <link rel="stylesheet" href="css/materialize.min.css">
    </head>
    <body>
        <div class="container">
            <textarea name="content" id="" cols="30" rows="10"></textarea>

        </div>
        <script src="ckeditor/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('content');
        </script>
    </body>
</html>
