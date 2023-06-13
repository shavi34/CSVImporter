<?php
class Helper
{
    public static function showHelpers()
    {
        echo "Usage: php your_script.php [options]\n";
        echo "Options:\n";
        echo "--file\t\t-f\t\tCSV file to process and add data to table - required\n";
        echo "--create_table\t-c\t\tUsers table to be built in DB, will truncate existing table -optional\n";
        echo "--dry_run\t-d\t\tAll functions will be executed, no data will be inserted into the DB -optional\n";
        echo "--user_name\t-u\t\tDatabase User name - required\n";
        echo "--password\t-p\t\tDatabase password - required\n";
        echo "--host\t\t-h\t\tDatabase host - required\n";
        die("--help\t\t-k\t\tShow this help message\n");
    }
}

// Command line argument definition
$short_options = "f:d::c::u:p:h:k::";
$long_options = ["file:", "dry_run", "create_table::", "user_name:", "password:", "host:", "help",];

$options = getopt($short_options, $long_options);
$help = array_key_exists('help', $options)|| array_key_exists('k', $options);
if ($help) {
    Helper::showHelpers();
}
