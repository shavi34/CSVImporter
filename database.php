<?php

class Database
{
  private $host;
  private $dbName;
  private $userName;
  private $password;
  private $rebuild;
  public $pdo;

  public function __construct($host, $dbName, $userName, $password, $rebuild)
  {
    $this->host = $host;
    $this->dbName = $dbName;
    $this->userName = $userName;
    $this->password = $password;
    $this->rebuild = $rebuild;
  }

  public function run()
  {
    $this->connectToDatabase();
    if ($this->rebuild) {
      $this->rebuildUsersTable();
    }
  }

  private function connectToDatabase()
  {
    $host = $this->host;
    $dbname = $this->dbName;
    $username = $this->userName;
    $password = $this->password;

    try {
      $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
    }
  }

  private function rebuildUsersTable()
  {
    $this->pdo->exec("DROP TABLE IF EXISTS users");
    $this->pdo->exec("CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            surname VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE
        )");
    echo "users table created successfully \n";
  }

  public function insertRecord($name, $surname, $email)
  {
    $statement = $this->pdo->prepare("INSERT INTO users (name, surname, email) VALUES (?, ?, ?)");

    try {
      $statement->execute([$name, $surname, $email]);
    } catch (PDOException $e) {
      print_r("Failed to insert data: " . $e->getMessage() . "\n");
    }
  }
}
