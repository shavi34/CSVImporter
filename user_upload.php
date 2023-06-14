<?php
require "database.php";
require "helpers.php";

class CsvImporter
{
  private $csvFile;
  private $db;
  private $dryRun;

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
