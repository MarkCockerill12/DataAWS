<?php
// configuration
$dbhost     = "localhost";
$dbname     = "test";
$dbuser     = "root";
$dbpass     = "admin";

// database connection
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

// form was submitted
if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['selectedOption'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $selectedOption = $_POST['selectedOption'];

    // add more validation and sanitizing if required

    // query using prepared statements
    if ($selectedOption == "SELECT") {
        $sql = "SELECT * FROM News WHERE Titolo_news = :title";
        $q = $conn->prepare($sql);
        $q->execute(array(':title' => $title));
        $q->setFetchMode(PDO::FETCH_ASSOC);
        while ($r = $q->fetch()) {
            echo $r['Titolo_news'] . " " . $r['Contenuto_news'] . "<br>";
        }
    } elseif ($selectedOption == "INSERT") {
        $sql = "INSERT INTO News (Titolo_news, Contenuto_news) VALUES (:title, :content)";
        $q = $conn->prepare($sql);
        $q->execute(array(':title' => $title, ':content' => $content));
        echo "Record inserted successfully.";
        
    } elseif ($selectedOption == "DELETE") {
        $sql = "DELETE FROM News WHERE Titolo_news = :title";
        $q = $conn->prepare($sql);
        $q->execute(array(':title' => $title));
        echo "Record deleted successfully.";

    } elseif ($selectedOption == "UPDATE") {
        $sql = "UPDATE News SET Contenuto_news = :content WHERE Titolo_news = :title";
        $q = $conn->prepare($sql);
        $q->execute(array(':title' => $title, ':content' => $content));
        echo "Record updated successfully.";

    } else {
        echo "Invalid operation.";
    }
}
?>