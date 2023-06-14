# **Script Task**

Commands to run the script

This PHP script is used to parse and process a CSV file containing user data and insert it into a MySQL database. The script is executed from the command line using the following commands:

`php user_upload.php --file users.csv -h <HOST> -u <USERNAME> -p <PASSWORD> -d <DBNAME>`

## Command Line Directives

### The script supports the following command line directives:

--file or -f [csv file name]: Specifies the name of the CSV file to be parsed. (Required)
--create_table or -c: Builds the MySQL users table and exits. No further action will be taken.
--dry_run or -r: Runs the script without inserting into the database. All other functions will be executed, but the database won't be altered. Should be used with the --file directive.
--user_name or -u: Specifies the MySQL username. (Required)
--password or -p: Specifies the MySQL password. (Required)
--host or -h: Specifies the MySQL host. (Required)
--database or -d: Specifies the MySQL database. (Required)
--help or -k: Outputs the list of directives with details.

## Example Usage

To parse the users.csv file and insert the data into the MySQL database, use the following command:

`php user_upload.php --file users.csv -h localhost -u myusername -p mypassword -d mydatabase`

Make sure to replace localhost, myusername, mypassword, and mydatabase with your actual database credentials.
For more information and detailed usage instructions, please refer to the command line directives above.
Feel free to modify and customize the script as needed for your specific requirements.


# **Logic Test**

`php fooBar.php`

## Number Manipulation

This PHP script is designed to be executed from the command line. It performs the following tasks:

Outputs the numbers from 1 to 100.
If a number is divisible by three (3), it outputs the word "foo".
If a number is divisible by five (5), it outputs the word "bar".
If a number is divisible by both three (3) and five (5), it outputs the word "foobar".
The script is contained within a single PHP file.

## Usage

Open a command-line interface.
Navigate to the directory where the PHP script is located.
Run the script using the PHP command: php script.php
