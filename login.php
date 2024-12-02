<?php
session_start();

$dbhost     = "steelsummit.caonv0ym8btc.us-east-1.rds.amazonaws.com";
$dbport     = "3306";
$dbname     = "SteelSummit";
$dbuser     = "admin";
$dbpass     = "WineGums";

try {
    // database connection
    $conn = new PDO("mysql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle AJAX request for UserInstanceID
if (isset($_GET['action']) && $_GET['action'] == 'getUserInstanceID') {
    header('Content-Type: application/json');
    if (isset($_SESSION['UserInstanceID'])) {
        echo json_encode(['UserInstanceID' => $_SESSION['UserInstanceID']]);
    } else {
        echo json_encode(['UserInstanceID' => null]);
    }
    exit();
}

// Handle logout action
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: login.html");
    exit();
}

if (isset($_POST["Iusername"], $_POST["Ipassword"])) {
    $Iusername = $_POST["Iusername"];
    $Ipassword = $_POST["Ipassword"];

    //Query to check ShopStaff
    $query = "SELECT ShopStaffID, Username, Password, Position FROM Shop_Staff WHERE Username = :username AND Password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $Iusername);
    $stmt->bindParam(':password', $Ipassword);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['UserInstanceID'] = $row['ShopStaffID'];
        $Department = "Shop";

        if ($row['Position'] == "Staff") {
            header("Location: staff.html");
            exit();
        } elseif ($row['Position'] == "Manager") {
            header("Location: manager.html");
            exit();
        } elseif ($row['Position'] == "CEO") {
            header("Location: ceo.html");
            exit();
        } else {
            echo "Unknown position.";
            exit();
        }

    } else {
        //query to check FactoryStaff
        $query = "SELECT FactoryStaffID, Username, Password, Position FROM Factory_Staff WHERE Username = :username AND Password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $Iusername);
        $stmt->bindParam(':password', $Ipassword);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['UserInstanceID'] = $row['FactoryStaffID'];
            $Department = "Factory";

            if ($row['Position'] == "Staff") {
                header("Location: staff.html");
                exit();
            } elseif ($row['Position'] == "Manager") {
                header("Location: manager.html");
                exit();
            } elseif ($row['Position'] == "CEO") {
                header("Location: ceo.html");
                exit();
            } else {
                echo "Unknown position.";
                exit();
            }

        } else {
            echo "Invalid username or password";
            exit();
        }
    }

    //Close connections
    $conn = null;
}
?>