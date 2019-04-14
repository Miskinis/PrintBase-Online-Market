<?php
require_once("database.php");

$m_database = Database::getInstance();
$m_connection = $m_database->getConnection();

$sql = "SELECT name, imagePath FROM category";

$result = $m_connection->query($sql);

// output data
echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
?>