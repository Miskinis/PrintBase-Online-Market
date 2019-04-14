<?php
require_once("database.php");

$componentID = $_REQUEST["componentID"];

$m_database = Database::getInstance();
$m_connection = $m_database->getConnection();

$sql = Database::$selectComponentCommand ." WHERE component.id=$componentID";

$result = $m_connection->query($sql);

// output data
echo json_encode($result->fetch(PDO::FETCH_ASSOC));
?>