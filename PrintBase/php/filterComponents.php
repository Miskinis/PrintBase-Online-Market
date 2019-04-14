<?php
require_once("database.php");

$categoryName = $_REQUEST["categoryName"];
$properties = json_decode($_REQUEST["properties"]);

$m_database = Database::getInstance();
$m_connection = $m_database->getConnection();

$sql = Database::$selectComponentCommand ." WHERE category.name=" . '"' . $categoryName . '"';

$m_count = 0;
foreach($properties as $key => $values)
{
    $sql .= ' AND (';
    $length = count($values);
    for($i = 0; $i < $length; $i++)
    {
        //var_dump($values[$i]);
        $sql .= "$key=" . '"' . $values[$i] . '"';
        if($length - 1 != $i)
            $sql .= ' OR ';
    }
    $sql .= ')';
}

$result = $m_connection->query($sql);

// output data
echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
?>