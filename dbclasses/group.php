<?php
class GroupDAO {
    private $config;
    function __construct($config) {
        $this->config = $config;
    }
    
    function createGroup($userId, $latitude, $longitude) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("INSERT INTO groups(name, datecreated) VALUES (?, ?, ?, ?)");
        $createDate = date("Y-m-d");
        $stmt->bind_param("sd", $name, $createDate);
        $stmt->execute();
        $stmt->close();
        
        $groupId = $mysqli->insert_id;
        $stmt2 = $mysqli->prepare("INSERT INTO usergroups(groupid, userid, latitude, longitude) VALUES (?, ?, 1)");
        $stmt2->bind_param("iidd", $groupId, $userId, $latitude, $longitude);
        $stmt2->execute();
        $stmt2->close();
        
        $mysqli->close();
    }
}
?>