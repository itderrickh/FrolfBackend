<?php
class GroupDAO {
    private $config;
    function __construct($config) {
        $this->config = $config;
    }
    
    function createGroup($name) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("INSERT INTO groups(name, datecreated) VALUES (?, ?)");
        $createDate = date("Y-m-d");
        
        $stmt->bind_param("sd", $name, $createDate);
        $stmt->execute();
        
        $stmt->close();
        $mysqli->close();
    }
}

?>