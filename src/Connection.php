<?php

class Connection
{
    protected $config;
    public $connect;

    public function __construct()
    {
        $this->setConfig();
        $this->connect = $this->getPDOConnection();
    }

    public function getConfig($config)
    {
        return $this->config['connection_1'][$config];
    }

    public function getPDOConnection()
    {
        $dsn = 'mysql:host='.$this->getConfig('host').';dbname='.$this->getConfig('database');
        try {
            $pdo = new PDO($dsn, $this->getConfig('user'), $this->getConfig('password'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch(PDOException $ex) {
           echo sprintf('Error: %s', $ex->getMessage());
        }
    }

    public function setConfig()
    {
        $this->config = include isset($_SERVER['REQUEST_URI']) ? \ROOT_PATH.'/config/db_config.php' : \ROOT_PATH.'/config/db_config.php';
    }
}

$db = (new Connection())->getPDOConnection();

?>