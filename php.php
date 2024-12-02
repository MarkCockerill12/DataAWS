<?php
include 'login.php';

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

// Fetch table data as JSON
if (isset($_GET['table'])) {
    $table = $_GET['table'];
    try {
        $stmt = $conn->prepare("SELECT * FROM $table");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
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
    $joinType = isset($_POST['joinType']) ? $_POST['joinType'] : 'none';
    $table = isset($_POST['Table']) ? $_POST['Table'] : '';
    $leftAttribute = isset($_POST['leftAttribute']) ? $_POST['leftAttribute'] : '';
    $rightAttribute = isset($_POST['rightAttribute']) ? $_POST['rightAttribute'] : '';

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

            // Add JOIN clause if specified
            if ($joinType !== 'none' && !empty($table) && !empty($leftAttribute) && !empty($rightAttribute)) {
                if ($joinType === 'full outer') {
                    $sql = "SELECT $title FROM $content LEFT JOIN $table ON $leftAttribute = $rightAttribute
                            UNION
                            SELECT $title FROM $content RIGHT JOIN $table ON $leftAttribute = $rightAttribute";
                } else {
                    $joinClause = strtoupper($joinType) . " JOIN";
                    $sql .= " $joinClause $table ON $leftAttribute = $rightAttribute";
                }
            }

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
                if ($whereRelation === 'NOT') {
                    $sql = str_replace("$whereField NOT :whereValue", "$whereField != :whereValue", $sql);
                }
            }
            if (isset($_POST['dynamicWhereField']) && isset($_POST['dynamicWhereValue']) && isset($_POST['dynamicWhereRelation']) && isset($_POST['dynamicWhereLogic'])) {
                $fields = $_POST['dynamicWhereField'];
                $values = $_POST['dynamicWhereValue'];
                $relations = $_POST['dynamicWhereRelation'];
                $logics = $_POST['dynamicWhereLogic'];
                $conditions = [];
                for ($i = 0; $i < count($fields); $i++) {
                    $condition = $logics[$i] . " " . $fields[$i] . " " . $relations[$i] . " :" . $fields[$i];
                    if ($relations[$i] === 'NOT') {
                        $condition = str_replace("$fields[$i] NOT :" . $fields[$i], "$fields[$i] != :" . $fields[$i], $condition);
                    }
                    $conditions[] = $condition;
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
            // Execute the query and return the results as JSON
            $q = $conn->prepare($sql);
            $q->execute($params);
            $result = $q->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
            exit;

        } elseif ($selectedOption == "INSERT") {
            // Separate table name and columns
            preg_match('/^(\w+)\s*\((.*)\)$/', $title, $matches);
            $tableName = $matches[1];
            $columns = array_map('trim', explode(',', $matches[2]));
        
            // Handle values correctly, considering potential commas within strings
            $values = [];
            $inQuotes = false;
            $currentValue = '';
            for ($i = 0; $i < strlen($content); $i++) {
                $char = $content[$i];
                if ($char == "'" && ($i == 0 || $content[$i - 1] != '\\')) {
                    $inQuotes = !$inQuotes;
                }
                if ($char == ',' && !$inQuotes) {
                    $values[] = trim($currentValue, " '");
                    $currentValue = '';
                } else {
                    $currentValue .= $char;
                }
            }
            $values[] = trim($currentValue, " '");
        
            // Handle dynamic fields
            if (isset($_POST['dynamicFromField'])) {
                $fields = $_POST['dynamicFromField'];
                for ($i = 0; $i < count($fields); $i++) {
                    $values[] = $fields[$i]; // Use the field value directly
                }
            }
        
            // Ensure the values array matches the columns array in length
            if (count($columns) !== count($values)) {
                echo json_encode(['error' => 'Column count does not match value count.']);
                exit;
            }
        
            $placeholders = implode(',', array_fill(0, count($values), '?'));
            $sql = "INSERT INTO $tableName (" . implode(',', $columns) . ") VALUES ($placeholders)";
            
            // Execute the query and return the result as JSON
            $q = $conn->prepare($sql);
            $q->execute($values);
            echo json_encode(['success' => 'Record inserted successfully.']);
            exit;

        } elseif ($selectedOption == "DELETE") {
            $sql = "DELETE FROM $title WHERE $whereField $whereRelation :whereValue";
            $params = [':whereValue' => $whereValue];
            if ($whereRelation === 'NOT') {
                $sql = str_replace("$whereField NOT :whereValue", "$whereField != :whereValue", $sql);
            }
            if (isset($_POST['dynamicWhereField']) && isset($_POST['dynamicWhereValue']) && isset($_POST['dynamicWhereRelation']) && isset($_POST['dynamicWhereLogic'])) {
                $fields = $_POST['dynamicWhereField'];
                $values = $_POST['dynamicWhereValue'];
                $relations = $_POST['dynamicWhereRelation'];
                $logics = $_POST['dynamicWhereLogic'];
                $conditions = [];
                for ($i = 0; $i < count($fields); $i++) {
                    $condition = $logics[$i] . " " . $fields[$i] . " " . $relations[$i] . " :" . $fields[$i];
                    if ($relations[$i] === 'NOT') {
                        $condition = str_replace("$fields[$i] NOT :" . $fields[$i], "$fields[$i] != :" . $fields[$i], $condition);
                    }
                    $conditions[] = $condition;
                    $params[":" . $fields[$i]] = $relations[$i] === 'LIKE' ? "%{$values[$i]}%" : $values[$i];
                }
                if (!empty($conditions)) {
                    $sql .= " " . implode(" ", $conditions);
                }
            }
            // Execute the query and return the result as JSON
            $q = $conn->prepare($sql);
            $q->execute($params);
            echo json_encode(['success' => 'Record deleted successfully.']);
            exit;

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
            if ($whereRelation === 'NOT') {
                $sql = str_replace("$whereField NOT :whereValue", "$whereField != :whereValue", $sql);
            }

            if (isset($_POST['dynamicWhereField']) && isset($_POST['dynamicWhereValue']) && isset($_POST['dynamicWhereRelation']) && isset($_POST['dynamicWhereLogic'])) {
                $fields = $_POST['dynamicWhereField'];
                $values = $_POST['dynamicWhereValue'];
                $relations = $_POST['dynamicWhereRelation'];
                $logics = $_POST['dynamicWhereLogic'];
                $conditions = [];
                for ($i = 0; $i < count($fields); $i++) {
                    $condition = $logics[$i] . " " . $fields[$i] . " " . $relations[$i] . " :" . $fields[$i];
                    if ($relations[$i] === 'NOT') {
                        $condition = str_replace("$fields[$i] NOT :" . $fields[$i], "$fields[$i] != :" . $fields[$i], $condition);
                    }
                    $conditions[] = $condition;
                    $params[":" . $fields[$i]] = $relations[$i] === 'LIKE' ? "%{$values[$i]}%" : $values[$i];
                }
                if (!empty($conditions)) {
                    $sql .= " " . implode(" ", $conditions);
                }
            }

            // Execute the query and return the result as JSON
            $q = $conn->prepare($sql);
            $q->execute($params);
            echo json_encode(['success' => 'Record updated successfully.']);
            exit;

        } else {
            echo json_encode(['error' => 'Invalid operation.']);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}
?>