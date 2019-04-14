<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//Connection via PDO
class Database {
	public static $selectComponentCommand = "SELECT component.id, component.imagePath, component.Description, component.Name, component.Price, component.In_Stock, brand.Brand, color.Color, size.Size, weight.Weight
FROM component
	INNER JOIN category
		ON component.category_id=category.id
	INNER JOIN brand
		ON component.brand_id=brand.id
	INNER JOIN color
		ON component.color_id=color.id
	INNER JOIN size
		ON component.size_id=size.id
	INNER JOIN weight
		ON component.weight_id=weight.id";
    private $m_connection;
    private static $m_instance;             //The single instance
    private $m_host = "printbase.eu";
    private $m_username = "root";
    private $m_password = "";
    private $m_database = "PrintBase";

    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance()
    {
        if (!self::$m_instance)             // If no instance then make one
        {
            self::$m_instance = new self();
        }
        return self::$m_instance;
    }

    // Constructor
    private function __construct()
    {
        try
        {
            $this->m_connection  = new \PDO("mysql:host=$this->m_host;dbname=$this->m_database",$this->m_username, $this->m_password);
            $this->m_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    // clone method empty to prevent duplication of connection
    private function __clone()  {}

    // Get mysql pdo connection
    public function getConnection()
    {
        return $this->m_connection;
    }
}
?>