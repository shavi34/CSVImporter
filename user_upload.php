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
    public $pdo;

    public function __construct($host, $dbName, $userName, $password)
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->userName = $userName;
        $this->password = $password;
    }

    public function run()
    {
        $this->connectToDatabase();
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
}

// Command line argument definition
$short_options = "f:r::c::u:p:h:d:k::";
$long_options = ["file:", "dry_run", "create_table::", "user_name:", "password:", "host:", "database:", "help",];

$options = getopt($short_options, $long_options);

$help = array_key_exists('help', $options) || array_key_exists('k', $options);
$csvFile = $options['f'] ?? $options['file'] ?? '';
$host = $options['h'] ?? $options['host'] ?? '';
$db = $options['d'] ?? $options['database'] ?? '';
$user = $options['u'] ?? $options['user_name'] ?? '';
$password = $options['p'] ?? $options['password'] ?? '';

if ($help) {
    Helper::showHelpers();
}

if (empty($csvFile)) {
    die("CSV file path is required. use --help for more info\n");
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
$db = new Database($host, $db, $user, $password);
$db->run();
