<?php 
    try{
        require __DIR__ . '/hidden.php';

        // Prod Connection
        // $conn = new \PDO("mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_NAME'].";charset=utf8mb4", $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);

        // Dev connection
        $conn = new \PDO($conn_dsn, $conn_user, $conn_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        echo "Database Connection Error, please check your connection file.";
        throw new \PDOException($e->getMessage());
    }
?>