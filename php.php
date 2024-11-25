<?php
// configuration
$dbhost     = "172.17.0.3";
$dbport     = "3306";
$dbname     = "SteelSummit";
$dbuser     = "root";
$dbpass     = "pass";

try {
    // database connection
    $conn = new PDO("mysql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// form was submitted
if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['selectedOption'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $selectedOption = $_POST['selectedOption'];
    $whereField = isset($_POST['whereField']) ? $_POST['whereField'] : '';
    $whereRelation = isset($_POST['whereRelation']) ? $_POST['whereRelation'] : '';
    $whereValue = isset($_POST['whereValue']) ? $_POST['whereValue'] : '';
    $groupBy = isset($_POST['groupBy']) ? $_POST['groupBy'] : 'none';
    $groupByValue = isset($_POST['groupByValue']) ? $_POST['groupByValue'] : '';
    $orderBy = isset($_POST['orderBy']) ? $_POST['orderBy'] : 'none';
    $orderByFieldType = isset($_POST['orderByFieldType']) ? $_POST['orderByFieldType'] : 'none';
    $orderByValue = isset($_POST['orderByValue']) ? $_POST['orderByValue'] : '';
    $as = isset($_POST['as']) && $_POST['as'] === 'as' ? $_POST['asValue'] : '';

    try {
        // query using prepared statements
        if ($selectedOption == "SELECT") {
            $sql = "SELECT $title";
            if ($as) {
                $sql .= " AS $as";
            }
            if (isset($_POST['dynamicTitleField']) && isset($_POST['dynamicTitleFieldType']) && isset($_POST['dynamicAs']) && isset($_POST['dynamicAsValue'])) {
                $fields = $_POST['dynamicTitleField'];
                $fieldTypes = $_POST['dynamicTitleFieldType'];
                $asFields = $_POST['dynamicAs'];
                $asValues = $_POST['dynamicAsValue'];
                for ($i = 0; $i < count($fields); $i++) {
                    $sql .= ", " . ($fieldTypes[$i] !== 'none' ? strtoupper($fieldTypes[$i]) . "({$fields[$i]})" : $fields[$i]);
                    if ($asFields[$i] === 'as' && !empty($asValues[$i])) {
                        $sql .= " AS {$asValues[$i]}";
                    }
                }
            }

            $sql .= " FROM $content";
            if (isset($_POST['dynamicFromField'])) {
                $fields = $_POST['dynamicFromField'];
                for ($i = 0; $i < count($fields); $i++) {
                    $sql .= ", " . $fields[$i];
                }
            }
            $params = [];

            if (!empty($whereField)) {
                $sql .= " WHERE $whereField $whereRelation :whereValue";
                $params[':whereValue'] = $whereRelation === 'LIKE' ? "%$whereValue%" : $whereValue;
            }
            if (isset($_POST['dynamicWhereField']) && isset($_POST['dynamicWhereValue']) && isset($_POST['dynamicWhereRelation']) && isset($_POST['dynamicWhereLogic'])) {
                $fields = $_POST['dynamicWhereField'];
                $values = $_POST['dynamicWhereValue'];
                $relations = $_POST['dynamicWhereRelation'];
                $logics = $_POST['dynamicWhereLogic'];
                $conditions = [];
                for ($i = 0; $i < count($fields); $i++) {
                    $conditions[] = $logics[$i] . " " . $fields[$i] . " " . $relations[$i] . " :" . $fields[$i];
                    $params[":" . $fields[$i]] = $relations[$i] === 'LIKE' ? "%{$values[$i]}%" : $values[$i];
                }
                if (!empty($conditions)) {
                    $sql .= " " . implode(" ", $conditions);
                }
            }
            if ($groupBy !== 'none' && !empty($groupByValue)) {
                $sql .= " GROUP BY $groupByValue";
            }
            if ($orderBy !== 'none' && !empty($orderByValue)) {
                $sql .= " ORDER BY " . ($orderByFieldType !== 'none' ? strtoupper($orderByFieldType) . "({$orderByValue})" : $orderByValue) . " $orderBy";
            }
            // Display the SQL command with the actual values
            $displaySql = $sql;
            foreach ($params as $key => $value) {
                $displaySql = str_replace($key, "'$value'", $displaySql);
            }
            echo "<button onclick='history.back()'>Go Back</button>";
            echo "<p>SQL Command: $displaySql</p>";
            $q = $conn->prepare($sql);
            $q->execute($params);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetch()) {
                foreach ($r as $column => $value) {
                    echo $column . ": " . $value . "<br>";
                }
                echo "<br>";
            }

        } elseif ($selectedOption == "INSERT") {
            // Separate table name and columns
            preg_match('/^(\w+)\s*\((.*)\)$/', $title, $matches);
            $tableName = $matches[1];
            $columns = array_map('trim', explode(',', $matches[2]));
        
            $values = array_map(function($value) {
                return $value !== null ? trim($value, " '") : '';
            }, explode(',', $content));
            $placeholders = implode(',', array_fill(0, count($values), '?'));
            $sql = "INSERT INTO $tableName (" . implode(',', $columns) . ") VALUES ($placeholders)";
            // Display the SQL command with the actual values
            $displaySql = $sql;
            foreach ($values as $key => $value) {
                $displaySql = preg_replace('/\?/', "'$value'", $displaySql, 1);
            }
            echo "<button onclick='history.back()'>Go Back</button>";
            echo "<p>SQL Command: $displaySql</p>";
            $q = $conn->prepare($sql);
            $q->execute($values);
            echo "Record inserted successfully.";

        } elseif ($selectedOption == "DELETE") {
            $sql = "DELETE FROM $title WHERE $whereField $whereRelation :whereValue";
            $params = [':whereValue' => $whereValue];
            if (isset($_POST['dynamicWhereField']) && isset($_POST['dynamicWhereValue']) && isset($_POST['dynamicWhereRelation']) && isset($_POST['dynamicWhereLogic'])) {
                $fields = $_POST['dynamicWhereField'];
                $values = $_POST['dynamicWhereValue'];
                $relations = $_POST['dynamicWhereRelation'];
                $logics = $_POST['dynamicWhereLogic'];
                $conditions = [];
                for ($i = 0; $i < count($fields); $i++) {
                    $conditions[] = $logics[$i] . " " . $fields[$i] . " " . $relations[$i] . " :" . $fields[$i];
                    $params[":" . $fields[$i]] = $relations[$i] === 'LIKE' ? "%{$values[$i]}%" : $values[$i];
                }
                if (!empty($conditions)) {
                    $sql .= " " . implode(" ", $conditions);
                }
            }
            // Display the SQL command with the actual values
            $displaySql = $sql;
            foreach ($params as $key => $value) {
                $displaySql = str_replace($key, "'$value'", $displaySql);
            }
            echo "<button onclick='history.back()'>Go Back</button>";
            echo "<p>SQL Command: $displaySql</p>";
            $q = $conn->prepare($sql);
            $q->execute($params);
            echo "Record deleted successfully.";

        } elseif ($selectedOption == "UPDATE") {
            // Retrieve dynamic fields for the SET clause
            $sql = "UPDATE $title";

            $sql .= " SET $content";
            if (isset($_POST['dynamicFromField'])) {
                $fields = $_POST['dynamicFromField'];
                for ($i = 0; $i < count($fields); $i++) {
                    $sql .= ", " . $fields[$i];
                }
            }
            $sql .= " WHERE $whereField $whereRelation :whereValue";
            $params[':whereValue'] = $whereRelation === 'LIKE' ? "%$whereValue%" : $whereValue;

            if (isset($_POST['dynamicWhereField']) && isset($_POST['dynamicWhereValue']) && isset($_POST['dynamicWhereRelation']) && isset($_POST['dynamicWhereLogic'])) {
                $fields = $_POST['dynamicWhereField'];
                $values = $_POST['dynamicWhereValue'];
                $relations = $_POST['dynamicWhereRelation'];
                $logics = $_POST['dynamicWhereLogic'];
                $conditions = [];
                for ($i = 0; $i < count($fields); $i++) {
                    $conditions[] = $logics[$i] . " " . $fields[$i] . " " . $relations[$i] . " :" . $fields[$i];
                    $params[":" . $fields[$i]] = $relations[$i] === 'LIKE' ? "%{$values[$i]}%" : $values[$i];
                }
                if (!empty($conditions)) {
                    $sql .= " " . implode(" ", $conditions);
                }
            }

            // Display the SQL command with the actual values
            $displaySql = $sql;
            foreach ($params as $key => $value) {
                $displaySql = str_replace($key, "'$value'", $displaySql);
            }
            echo "<button onclick='history.back()'>Go Back</button>";
            echo "<p>SQL Command: $displaySql</p>";
            $q = $conn->prepare($sql);
            $q->execute($params);
            echo "Record updated successfully.";

        } else {
            echo "Invalid operation.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>