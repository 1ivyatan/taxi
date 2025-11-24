<?php

require_once "credentials.php";

class Database {
    private $conn;

    public function __construct() {
        try {
            $credentials = new DbLogin();

            $this->conn = new PDO("mysql:host=".$credentials->getServer().";charset=utf8;dbname=".$credentials->getDb(), $credentials->getUsr(), $credentials->getPasswd());

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "Nevarēja savienoties ar datusbāzi: ".$e->getMessage();
            die;
        }
    }

    public function query(string $query, bool $doReturn, ...$items) {
        $stmt = $this->conn->prepare($query);

		foreach ($items as $item) {
			if ($item == null) continue;
			$stmt->bindParam($item->name, $item->value, $item->type);
		}

		if ($stmt->execute()) {
			if ($doReturn == true) {
				return $stmt->fetchAll();
			} else {
				return array("true" => "true");
			}
			
		} else {
			return array();
		}

    }
}

class SQLQueryItem {
	public int $type;
	public string $name;
	public string $value;

	public function __construct(int $type, string $name, string $value) {
		$this->type = $type;
		$this->name = $name;
		$this->value = $value;
	}
}

$db = new Database();