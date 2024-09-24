<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter produit</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php require_once('header.php'); ?>
    <div class="body">

        <?php
        session_start();
        if (isset($_SESSION['message'])) {
            echo '<div id="info-message">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>
        <div class="forms">
            <div title="Tooltip">
                <h1>Ajouter un produit</h1>
            </div>
            <form action="traitement.php?action=add" method="post" class="main_forms" enctype="multipart/form-data"> <!-- enctype -->
                <p>
                    <label>
                        Nom du produit : *
                        <input type="text" name="name" class="color">
                    </label>
                </p>
                <p>
                    <label>
                        Prix du produit : *
                        <input type="number" step="any" name="price" min="0" class="color">
                    </label>
                </p>
                <p>
                    <label>
                        Quantité désirée : *
                        <input type="number" name="qtt" value="1" min="0" class="color">
                    </label>
                </p>
                <p>
                    <label for="description">
                        Description *
                    </label>
                    <textarea name="description" id="description" class="color"></textarea><!--color-->
                </p>
                <p>
                    <label for="file">Photo</label>
                    <input type="file" name="file">
                </p>
                <p>
                    <input type="submit" name="submit" value="Ajouter le produit" class="submit" class="color">
                </p>
                <p>Les champs avec * doivent obligatoirement être remplis.</p>
            </form>
        </div>
    </div>



    <?php

    require './bdd.php';

    if (isset($_FILES['file'])) {
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif'];
        $maxSize = 400000;

        if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName . "." . $extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, './upload/' . $file);

            $req = $db->prepare('INSERT INTO file (name) VALUES (?)');
            $req->execute([$file]);

            echo "Image enregistrée";
        } else {
            echo "Une erreur est survenue";
        }
    }

    ?>

    <h2>Mes images</h2>
    <?php
    $req = $db->query('SELECT name FROM file');
    while ($data = $req->fetch()) {
        echo "<img src='./upload/" . $data['name'] . "' width='300px' ><br>";
    }
    ?>



    <script>
        // https://stackoverflow.com/questions/5988909/php-echo-message-for-a-specified-amount-of-time
        setTimeout(function() {
            document.getElementById('info-message').style.display = 'none';
            /* or
            var item = document.getElementById('info-message')
            item.parentNode.removeChild(item); 
            */
        }, 5000);
    </script>
</body>

</html>