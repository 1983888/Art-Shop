<?php
session_start();

$fixed_password = "WeKnowTheGame23";

if (isset($_POST["password"]) && $_POST["password"] === $fixed_password) {
    $_SESSION["authenticated"] = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Section</title>
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
            padding: 10px 14px
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

        #order_list, #add_painting, #remove_order, #login {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f0f5e1;
            border-radius: 8px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }


    </style>
</head>
<body>
<!-- Where I looked for my bootstrap nav bar code https://getbootstrap.com/docs/4.0/components/navbar/ -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="admin.php">Admin Section</a>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Art Home Page</a>
            </li>
        </ul>
    </div>
</nav>

    <?php
    // Where I got the code for ob_start() https://stackoverflow.com/questions/4401949/whats-the-use-of-ob-start-in-php
    ob_start();

    if (isset($_SESSION["authenticated"]) && $_SESSION["authenticated"] === true) {
        echo '<div id="order_list">';
        echo '<h2>Order List</h2>';

        //Connect to MySql
        $host = "devweb2023.cis.strath.ac.uk";
        $user = "pkb21154";
        $pass = "izohfoof5Ier";
        $dbname = $user;
        $conn = new mysqli($host, $user, $pass, $dbname);

        if ($conn->connect_error){
            die("Connection failed : ".$conn->connect_error); //FIXME remove details once working
        }

        $sql = "SELECT * FROM `ordering`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<table border="1">';
            echo '<tr><th>Order ID</th><th>Art ID</th><th>Name</th><th>Phone Number</th><th>Email</th><th>Postal Address</th></tr>';

            while ($row = $result->fetch_assoc()){
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['art_id'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['phone_number'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td>' . $row['postal_address'] . '</td>';
            }
            echo '</table>';
        } else {
            echo "No orders found in the database.";
        }

        echo '<div>';

        echo '<div id="add_painting">';
        echo '<h2>Add new painting</h2>';

        echo '<form action="admin.php" method="post" onsubmit="return validateForm()">';
        echo '<label for="name">Painting Name:</label>';
        echo '<input type="text" name="name" id="name"><br>';
        echo '<label for="date_of_completion">Date of Completion(YYYY/MM/DD):</label>';
        echo '<input type="text" name="date_of_completion" id="date_of_completion"><br>';
        echo '<label for="width_mm">Width(mm):</label>';
        echo '<input type="text" name="width_mm" id="width_mm"><br>';
        echo '<label for="height_mm">Height(mm):</label>';
        echo '<input type="text" name="height_mm" id="height_mm"><br>';
        echo '<label for="price">Price :£</label>';
        echo '<input type="text" name="price" id="price"><br>';
        echo '<label for="description">Description:</label>';
        echo '<input type="text" name="description" id="description"><br>';
        echo '<input type="submit" name="add_painting" value="Add Painting">';
        echo '</form>';

        if (isset($_POST["add_painting"])) {

            $name = $conn->real_escape_string(strip_tags($_POST["name"]));
            $date_of_completion = $conn->real_escape_string(strip_tags($_POST["date_of_completion"]));
            $width_mm = intval($_POST["width_mm"]);
            $height_mm = intval($_POST["height_mm"]);
            $price = floatval($_POST["price"]);
            $description = $conn->real_escape_string(strip_tags($_POST["description"]));

            $sql = "INSERT INTO `art`(`name`, `date_of_completion`, `width_mm`, `height_mm`, `price`, `description`) VALUES ('$name', '$date_of_completion', $width_mm, $height_mm, $price, '$description')";
            if ($conn->query($sql) === TRUE) {
                echo "Painting added successfully!";
            }
        }

        echo '</div>';

        echo '<div id="remove_order">';
        echo '<h2>Remove Order</h2>';
        echo '<form action="admin.php" method="post">';
        echo '<label for="order_id">Select Order ID to Remove:</label>';
        echo '<select name="order_id" id="order_id">';

        $sql = "SELECT id FROM `ordering` ORDER BY id";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . $row['id'] . '</option>';
        }

        echo '</select><br>';
        echo '<input type="submit" name="remove_order" value="Remove Order">';
        echo '</form>';

        if (isset($_POST["remove_order"])) {
            $order_id = intval($_POST['order_id']);
            $sql = "DELETE FROM `ordering` WHERE id = $order_id";
            if ($conn->query($sql) === TRUE) {
                // Where I got code for doing header https://blog.hubspot.com/website/php-redirect#:~:text=For%20instance%2C%20your%20header%20function,()%20or%20exit()%20function.
                header("Location: admin.php?order_removed=true");
                exit();
            }
        }

        echo '</div>';

    } else {
        echo '<div id="login">';
        echo '<h2>Login</h2>';
        echo '<form action="admin.php" method="post">';
        echo '<label for="password">Password:</label>';
        echo '<input type="password" name="password" id="password"><br>';
        echo '<input type="submit" value="Login">';
        echo '</form>';
        echo '</div>';
    }


    ob_end_flush();
    ?>

<script>

    function validateForm() {
        var name = document.getElementById('name').value;
        var date_of_completion = document.getElementById('date_of_completion').value;
        var width_mm = document.getElementById('width_mm').value;
        var height_mm = document.getElementById('height_mm').value;
        var price = document.getElementById('price').value;
        var description = document.getElementById('description').value;

        if (name === '' || date_of_completion === '' || width_mm === '' || height_mm === '' || price === ''|| description === '') {
            alert('Please fill in all fields');
            return false;
        }

        return true;
    }
</script>
</body>
</html>