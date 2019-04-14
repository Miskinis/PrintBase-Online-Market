<?php
require_once("database.php");

$componentName = $_REQUEST["componentName"];

$m_database = Database::getInstance();
$m_connection = $m_database->getConnection();

$sql = Database::$selectComponentCommand ." WHERE component.Name LIKE" . '"%' . $componentName . '%"';

$result = $m_connection->query($sql);

// output data
echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
?>