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
    $whereField = isset($_POST['whereField']) ? $_POST['whereField'] : '';
    $whereRelation = isset($_POST['whereRelation']) ? $_POST['whereRelation'] : '';
    $whereValue = isset($_POST['whereValue']) ? $_POST['whereValue'] : '';

    // query using prepared statements
    if ($selectedOption == "SELECT") {
        $sql = "SELECT $title FROM $content";
        $params = [];
        if (!empty($whereField)) {
            $sql .= " WHERE $whereField $whereRelation :whereValue";
            $params[':whereValue'] = $whereValue;
        }
        if (isset($_POST['dynamicWhereField']) && isset($_POST['dynamicWhereValue']) && isset($_POST['dynamicWhereRelation']) && isset($_POST['dynamicWhereLogic'])) {
            $fields = $_POST['dynamicWhereField'];
            $values = $_POST['dynamicWhereValue'];
            $relations = $_POST['dynamicWhereRelation'];
            $logics = $_POST['dynamicWhereLogic'];
            $conditions = [];
            for ($i = 0; $i < count($fields); $i++) {
                $conditions[] = $logics[$i] . " " . $fields[$i] . " " . $relations[$i] . " :" . $fields[$i];
                $params[":" . $fields[$i]] = $values[$i];
            }
            if (!empty($conditions)) {
                $sql .= " " . implode(" ", $conditions);
            }
        }
        $q = $conn->prepare($sql);
        $q->execute($params);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        while ($r = $q->fetch()) {
            echo $r['Titolo_news'] . " " . $r['Contenuto_news'] . "<br>";
        }

    } elseif ($selectedOption == "INSERT") {
        $sql = "INSERT INTO $title ($content) VALUES (:content)";
        $q = $conn->prepare($sql);
        $q->execute(array(':content' => $content));
        echo "Record inserted successfully.";
        // database insert SQL code
        // $sql = "INSERT INTO `tbl_contact` (`Id`, `fldName`, `fldEmail`, `fldPhone`, `fldMessage`) VALUES ('0', '$txtName', '$txtEmail', '$txtPhone', '$txtMessage')";
        // insert in database 
        // $rs = mysqli_query($con, $sql);
        // if($rs) {   
        // 	echo "Contact Records Inserted";}

    } elseif ($selectedOption == "DELETE") {
        $sql = "DELETE FROM $title WHERE $content = :content";
        $q = $conn->prepare($sql);
        $q->execute(array(':content' => $content));
        echo "Record deleted successfully.";

    } elseif ($selectedOption == "UPDATE") {
        $sql = "UPDATE $title SET $content = :content";
        $q = $conn->prepare($sql);
        $q->execute(array(':content' => $content));
        echo "Record updated successfully.";

    } else {
        echo "Invalid operation.";
    }
}
?>