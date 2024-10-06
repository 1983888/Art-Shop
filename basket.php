<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Basket</title>
    <!-- Bootstrap CDN links -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f5e1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        img {
            width: 250px;
            height: 200px;
        }

        p {
            text-align: center;
            color: #333;
        }

        a {
            display: block;
            text-align: center;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            text-decoration: none;
            color: white;
        }

        nav {
            background-color: #333;
            overflow: hidden;
            text-align: right;
            width: 100%;
        }

        nav a {
            display: inline-block;
            color: #f2f2f2;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        .container {
            max-width: 500px;
            width: 100%;
        }

        .art_item {
            text-align: center;
            margin: 15px auto;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .order_link {
            background-color: #007bff;
        }

        .order_link:hover {
            background-color: #0056b3;
        }

        .empty_basket {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
<!-- Where I looked for my bootstrap nav bar code https://getbootstrap.com/docs/4.0/components/navbar/ -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Basket</a>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Art Home Page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin.php">Admin Login</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <?php

    if (isset($_SESSION['basket']) && count($_SESSION['basket']) > 0) {

        //Connect to MySql
        $host = "devweb2023.cis.strath.ac.uk";
        $user = "pkb21154";
        $pass = "izohfoof5Ier";
        $dbname = $user;
        $conn = new mysqli($host, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("Connection failed : ".$conn->connect_error); //FIXME remove details once working
        }

        foreach ($_SESSION['basket'] as $art_id) {
            $sql = "SELECT * FROM `art` WHERE id = $art_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                echo '<div class="art_item">';
                echo '<p>Painting Name: ' . $row['name'] . '</p>';
                // Where I got code for converting a blob to image https://stackoverflow.com/questions/6106470/php-convert-a-blob-into-an-image-file
                echo '<img src="data:image/jpeg;base64,'.base64_encode($row['image']) . '" alt="Art Image" /><br>';
                echo '<p>Price: £' . $row['price'] . '</p>';
                echo '</div>';
            }
        }

        echo '</table>';

        $conn->close();

        echo '<a class="order_link" href="order.php?art_id=' . $art_id . '">Proceed to Order</a>';
    } else {
        echo '<p class="empty_basket">Your basket is empty.</p>';
    }

    ?>
</div>

</body>
</html>
