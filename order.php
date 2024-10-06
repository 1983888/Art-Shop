<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Form</title>
    <!-- Bootstrap CDN links -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f5e1;
        }

        h1 {
            text-align: left;
            color: #333;
            padding: 10px 14px;
        }

        img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        nav {
            background-color: #333;
            overflow: hidden;
            text-align: right;
            margin-bottom: 20px;
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

        .details_container {
            display: flex;
            justify-content: space-between;
        }

        #art-details {
            flex-basis: 45%;
            margin-right: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #order-form {
            flex-basis: 45%;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block:
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<!-- Where I looked for my bootstrap nav bar code https://getbootstrap.com/docs/4.0/components/navbar/ -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Order</a>
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
    <?php

    if(isset($_GET["art_id"]) && is_numeric($_GET["art_id"])) {
        $art_id = intval($_GET["art_id"]);

        //Connect to MySql
        $host = "devweb2023.cis.strath.ac.uk";
        $user = "pkb21154";
        $pass = "izohfoof5Ier";
        $dbname = $user;
        $conn = new mysqli($host, $user, $pass, $dbname);

        if ($conn->connect_error){
            die("Connection failed : ".$conn->connect_error); //FIXME remove details once working
            }

        $sql = "SELECT * FROM `art` WHERE id = $art_id";
        $result = $conn->query($sql);

        ?>

        <div class="container">
            <div class="details_container">
                <div id="art-details">
                    <?php

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo '<div>';
                        // Where I got code for converting a blob to image https://stackoverflow.com/questions/6106470/php-convert-a-blob-into-an-image-file
                        echo '<img src="data:image/jpeg;base64,'.base64_encode($row['image']) . '" alt="Art Image" />';
                        echo '<p>Painting Name: ' . $row['name'] . '</p>';
                        echo '<p>Date of Completion: ' . $row['date_of_completion'] . '</p>';
                        echo '<p>Width(mm): ' . $row['width_mm'] . '</p>';
                        echo '<p>Height(mm): ' . $row['height_mm'] . '</p>';
                        echo '<p>Price: £' . $row['price'] . '</p>';
                        echo '<p>Description: ' . $row['description'] . '</p>';
                        echo '</div>';
                    } else {
                        echo "Artwork not found in the database.";
                    }
                    ?>
                </div>

                <div id="order-form">
                <?php

                echo '<form action="order.php?art_id=' . $art_id . '" method="post" onsubmit="return validateForm()">';
                echo '<h1>Order Form</h1>';
                echo '<label for="name">Name:</label>';
                echo '<input type="text" name="name" id="name"><br>';
                echo '<label for="phone_number">Phone Number:</label>';
                echo '<input type="text" name="phone_number" id="phone_number"><br>';
                echo '<label for="email">Email:</label>';
                echo '<input type="text" name="email" id="email"><br>';
                echo '<label for="postal_address">Postal Address:</label>';
                echo '<input type="text" name="postal_address" id="postal_address"><br>';
                echo '<input type="submit" value="Place Order">';
                echo '</form>';

                if (isset($_POST["name"]) && isset($_POST["phone_number"]) && isset($_POST["email"]) && isset($_POST["postal_address"])) {
                    $name = $conn->real_escape_string(strip_tags($_POST["name"]));
                    $phone_number = $conn->real_escape_string(strip_tags($_POST["phone_number"]));
                    $email = $conn->real_escape_string(strip_tags($_POST["email"]));
                    $postal_address = $conn->real_escape_string(strip_tags($_POST["postal_address"]));

                    $sql = "INSERT INTO `ordering`(`art_id`, `name`, `phone_number`, `email`, `postal_address`) VALUES ('$art_id', '$name', '$phone_number', '$email', '$postal_address')";
                    if ($conn->query($sql) === TRUE) {
                        echo "Order placed successfully!";
                    } else {
                        echo "Error placing the order: " . $conn->error;
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <?php

    $conn->close();
} else {
    echo "Invalid art ID provided.";
}
    ?>

    <script>

        function validateForm() {
            var name = document.getElementById('name').value;
            var phoneNumber = document.getElementById('phone_number').value;
            var email = document.getElementById('email').value;
            var postalAddress = document.getElementById('postal_address').value;


            if (name === '' || phoneNumber === '' || email === '' || postalAddress === '') {
                alert('Please fill in all fields');
                return false;
            }

            // Code for phone number regular expression https://www.w3schools.blog/phone-number-validation-javascript-js
            var phonePattern = /^\d+(-\d+)*$/;
            if (!phonePattern.test(phoneNumber)) {
                alert('Please enter a valid phone number');
                return false;
            }

            // Code for email regular expression https://stackoverflow.com/questions/46155/how-can-i-validate-an-email-address-in-javascript
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address');
                return false;
            }

            return true;
        }
    </script>

</body>
</html>