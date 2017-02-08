<?php
    header('Content-Type: text/html; charset=utf-8');

    //Enter your database connection details here.
    $host = 'localhost'; //HOST NAME.
    $db_name = 'testup'; //Database Name
    $db_username = 'admin'; //Database Username
    $db_password = 'dr3113$0A'; //Database Password

    try
    {
        $pdo = new PDO('mysql:host='. $host .';dbname='.$db_name.';charset=utf8', $db_username, $db_password);
    }
    catch (PDOException $e)
    {
      echo 'Connection failed: ' . $e->getMessage();
      exit('Error Connecting To DataBase');
    }
?>
