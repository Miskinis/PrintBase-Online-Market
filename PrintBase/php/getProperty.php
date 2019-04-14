<?php
require_once("database.php");

$propertyName = $_REQUEST["propertyName"];

$m_database = Database::getInstance();
$m_connection = $m_database->getConnection();

$sql = "SELECT $propertyName FROM " . strtolower($propertyName);

$result = $m_connection->query($sql);

if ($result->rowCount() > 0)
{
    // output data of each row
	while($row = $result->fetch(PDO::FETCH_ASSOC))
	{
		$m_array[] = $row[$propertyName];
	}

    echo json_encode($m_array);
}
else
{
    echo "0 results";
}
?>