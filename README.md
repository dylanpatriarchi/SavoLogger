# SavoLogger
The SavoLogger library is a library that allows you to use the connection to a database in PHP in a secure way, prevents SQL Injections and writes all the actions that the user takes in a log file, thanks to the SavoLogger class. The strong point of this library is that, based on a pre-established pattern, it detects suspicious actions and sends an alert to the administrator user via email.

## Use SavoDB
### Define Credentials

Define your credentials on <b>mail_credentials.php</b>
```php
define("HOST", "smtp.example.com");
define("USERNAME", "your_username");
define("PASSWORD", "your_password");
define("SMTPSECURE", "tls");
define("PORT", 587);
define("SENDERADR", "your@email.com");
define("SENDERNAME", "your_name");
define("ADMINADR", "admin@email.com");
```

### Description
SavoDB is a PHP database interaction library designed to simplify database operations and provide enhanced logging capabilities. It encapsulates the functionality of PDO (PHP Data Objects) while integrating seamlessly with SavoLogger for logging database queries and activities.

### Features

1. <b>PDO Wrapper</b>: SavoDB wraps PDO to facilitate database connections and execute SQL queries.
2. <b>Logging Integration</b>: Automatically logs executed queries using SavoLogger, providing detailed insight into database activities.
3. <b>Parameter Binding</b>: Supports parameter binding to prevent SQL injection vulnerabilities.
4. <b>Error Handling</b>: Utilizes PDO's error handling mechanism to ensure robust error management.

### Installation
Include the savodb.php file in your PHP project.
Ensure that the necessary PDO drivers are enabled in your PHP configuration.

#### Usage
```php
require_once 'savodb.php';

// Initialize SavoDB
$db = new SavoDB('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

// Example query execution
$db->query('SELECT * FROM users WHERE id = :id');
$db->bind(':id', 123);
$result = $db->single();

// Logging executed queries
echo $db->getLastQuery(); // Output last executed query
```

#### Example
```php
require_once 'savodb.php';

// Initialize SavoDB
$db = new SavoDB('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

// Example: Selecting user by ID
$db->query('SELECT * FROM users WHERE id = :id');
$db->bind(':id', 123);
$user = $db->single();

// Example: Inserting a new user
$db->query('INSERT INTO users (username, email) VALUES (:username, :email)');
$db->bind(':username', 'john_doe');
$db->bind(':email', 'john@example.com');
$db->execute();

// Example: Updating user email
$db->query('UPDATE users SET email = :email WHERE id = :id');
$db->bind(':id', 123);
$db->bind(':email', 'new_email@example.com');
$db->execute();

// Example: Deleting user by ID
$db->query('DELETE FROM users WHERE id = :id');
$db->bind(':id', 123);
$db->execute();
```