<?php

class DB
{
  private static $instance = null;

  private $connection;

  private function __construct()
  {
    $user = 'root';
    $pass = '';
    $db = 'assignment1v6';
    $host = 'localhost';

    $this->connection = new mysqli($host, $user, $pass, $db);

    if ($this->connection->connect_error) {
      die('Connection failed: ' . $this->connection->connect_error);
    }
  }

  public static function getInstance()
  {
    if (self::$instance == null) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function getConnection()
  {
    return $this->connection;
  }
}
