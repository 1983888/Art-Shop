<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Section</title>
</head>
<body>
    <h1>Admin Section</h1>

    <?php

    session_start();

    if (isset($_GET["art_id"]) && is_numeric($_GET["art_id"])) {
        $art_id = intval($_GET["art_id"]);

        if (!isset($_SESSION['basket'])) {
            $_SESSION['basket'] = [];
        }

        $_SESSION['basket'][] = $art_id;

        // Where I got code for doing header https://blog.hubspot.com/website/php-redirect#:~:text=For%20instance%2C%20your%20header%20function,()%20or%20exit()%20function.
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid art ID provided";
    }

    ?>

</body>
</html>

