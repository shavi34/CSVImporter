**Script Task**
Commands to run the script

`php user_upload.php --file users.csv -h <HOST> -u <USERNAME> -p <PASSWORD> -d <DBNAME> `

--file or -f [csv file name]: Specifies the name of the CSV file to be parsed. (Required)

--create_table or -c: Builds the MySQL users table and exits. No further action will be taken.

--dry_run or -r: Runs the script without inserting into the database. All other functions will be executed, but the database won't be altered. Should be used with the --file directive.

--user_name or -u: Specifies the MySQL username. (Required)

--password or -p: Specifies the MySQL password. (Required)

--host or -h: Specifies the MySQL host. (Required)

--database or -d: Specifies the MySQL database. (Required)

--help or -k: Outputs the list of directives with details.
