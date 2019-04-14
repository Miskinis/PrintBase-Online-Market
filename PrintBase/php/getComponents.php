<?php
require_once("database.php");

$categoryName = $_REQUEST["categoryName"];

$m_database = Database::getInstance();
$m_connection = $m_database->getConnection();

$sql = Database::$selectComponentCommand ." WHERE category.name=" . '"' . $categoryName . '"';

$result = $m_connection->query($sql);

// output data
echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
?>