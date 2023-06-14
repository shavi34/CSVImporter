<?php

class Helper
{
    public static function showHelpers()
    {
        echo "Usage: php your_script.php [options]\n";
        echo "Options:\n";
        echo "--file\t\t-f\t\tCSV file to process and add data to table - required\n";
        echo "--create_table\t-c\t\tUsers table to be built in DB, will truncate existing table -optional\n";
        echo "--dry_run\t-r\t\tAll functions will be executed, no data will be inserted into the DB -optional\n";
        echo "--user_name\t-u\t\tDatabase User name - required\n";
        echo "--password\t-p\t\tDatabase password - required\n";
        echo "--host\t\t-h\t\tDatabase host - required\n";
        echo "--database\t\t-d\t\tDatabase name - required\n";
        die("--help\t\t-k\t\tShow this help message\n");
    }
}

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

class CsvImporter
{
    private $csvFile;
    private $db;

    public function __construct($csvFile, $db, $dryRun)
    {
        $this->csvFile = $csvFile;
        $this->db = $db;
         $this->dryRun = $dryRun;
    }

    public function run()
    {
        $this->processCsv();
    }


    private function processCsv()
    {
        $file = fopen($this->csvFile, 'r');
        if (!$file) {
            die("Failed to open CSV file.\n");
        }

        while (($data = fgetcsv($file)) !== false) {
            $name = ucfirst(strtolower($data[0]));
            $surname = ucfirst(strtolower($data[1]));
            $email = strtolower($data[2]);

            if ($this->validateEmail($email)) {
                $this->db->insertRecord($name, $surname, $email);
            }
        }

        fclose($file);
        if ($this->dryRun) {
            die("Dry Run :- CSV data processed, No data inserted into the 'users' table. \n");
        }
        echo "CSV data successfully inserted into the 'users' table.\n";
    }

    private function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email: $email\n";
            return false;
        }

        return true;
    }
}

// Command line argument definition
$short_options = "f:r::c::u:p:h:d:k::";
$long_options = ["file:", "dry_run", "create_table::", "user_name:", "password:", "host:", "database:", "help",];

$options = getopt($short_options, $long_options);
$help = array_key_exists('help', $options) || array_key_exists('k', $options);
$dryRun = array_key_exists('r', $options) || array_key_exists('dry_run', $options);
$rebuild = isset($options['c']) || isset($options['create_table']);
$csvFile = $options['f'] ?? $options['file'] ?? '';
$host = $options['h'] ?? $options['host'] ?? '';
$db = $options['d'] ?? $options['database'] ?? '';
$user = $options['u'] ?? $options['user_name'] ?? '';
$password = $options['p'] ?? $options['password'] ?? '';

if ($help) {
    Helper::showHelpers();
}

if (empty($host)) {
    die("Mysql host is required. use --help for more info\n");
}
if (empty($db)) {
    die("Mysql database name is required. use --help for more info\n");
}
if (empty($user)) {
    die("Mysql username is required. use --help for more info\n");
}
if (empty($password)) {
    die("Mysql password is required. use --help for more info\n");
}

// Run the script
$db = new Database($host, $db, $user, $password, $rebuild);
$db->run();

if (!empty($csvFile)) {
    $csvImporter = new CsvImporter($csvFile, $db, $dryRun);
    $csvImporter->run();
}
