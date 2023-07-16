
<?php
class Database
{
    private $db;

    public function __construct($dsn, $username, $password)
    {
        try {
            $this->db = new PDO($dsn, $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
            include('view/error.php');
            exit();
        }
    }

    public function prepare($query)
    {
        return $this->db->prepare($query);
    }

    public function executeQuery($query, $params = [])
    {
        $statement = $this->prepare($query);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function executeNonQuery($query, $params = [])
    {
        $statement = $this->prepare($query);
        return $statement->execute($params);
    }
}
