<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Art Shop</title>
    <!-- Bootstrap CDN links -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f5e1;
        }

        h1 {
            text-align: left;
            color: #ffffff;
            padding: 10px 14px;
        }

        img {
            width:250px;
            height:200px;
        }

        nav {
            background-color: #333;
            overflow: hidden;
            text-align: right;
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

        .art_container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 20px;
        }

        .art_item {
            text-align: center;
            margin: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }

        .order_link {
            display: block;
            margin-top: 10px;
            padding: 8px 12px;
            text-align: center;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }

        .basket_link {
            display: block;
            margin-top: 10px;
            padding: 8px 12px;
            text-align: center;
            color: #fff;
            background-color: #ffA500;
            text-decoration: none;
            border-radius: 5px;

        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .order_link:hover {
            background-color: #0056b3;
            color: #ffffff;
            text-decoration: none;
        }

        .basket_link:hover {
            background-color: #ff8c00;
            color: #ffffff;
            text-decoration: none;
        }

        .pagination button {
            background-color: #007bff;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            margin: 0 5px;
            text-decoration: none;
            cursor: pointer;
            display: inline-block;
        }

        .pagination button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
<!-- Where I looked for my bootstrap nav bar code https://getbootstrap.com/docs/4.0/components/navbar/ -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Art Shop</a>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="basket.php">View Basket</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin.php">Admin Login</a>
            </li>
        </ul>
    </div>
</nav>
    <div class="art_container">
    <?php

    $items_per_page = 12;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    //Connect to MySql
    $host = "devweb2023.cis.strath.ac.uk";
    $user = "pkb21154";
    $pass = "izohfoof5Ier";
    $dbname = $user;
    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed : ".$conn->connect_error); //FIXME remove details once working
    }

    $offset = ($current_page - 1) * $items_per_page;

    $sql = "SELECT * FROM `art` LIMIT $items_per_page OFFSET $offset;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="art_item">';
            echo '<p>Painting Name: ' . $row['name'] . '</p>';
            // Where I got code for converting a blob to image https://stackoverflow.com/questions/6106470/php-convert-a-blob-into-an-image-file
            echo '<img src="data:image/jpeg;base64,'.base64_encode($row['image']) .'" alt="Art Image" /><br>';
            echo '<a class="order_link" href="order.php?art_id=' . $row['id'] . '">Order Now</a>';
            echo '<a class="basket_link" href="addbasket.php?art_id=' . $row['id'] . '">Add to Basket</a>';
            echo '</div>';
        }

        ?>

    </div>

    <div class="pagination">
        <?php

        $previous_page = $current_page - 1;
        $next_page = $current_page + 1;

        if ($previous_page > 0) {
            echo '<a href="?page=' . $previous_page . '"><button>Previous Page</button></a>';
        }
        echo '<a href="?page=' . $next_page . '"><button>Next Page</button></a>';
        echo '</div>';
    } else {
        echo "No art for this page.";
    }

    $conn->close();
    ?>

</body>
</html>
