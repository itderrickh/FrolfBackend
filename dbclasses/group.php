<?php
date_default_timezone_set('UTC');
class GroupDAO {
    private $config;
    function __construct($config) {
        $this->config = $config;
    }
    
    function createGroup($userId, $latitude, $longitude) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("INSERT INTO groups(name, datecreated, latitude, longitude) VALUES (?, NOW(), ?, ?)");
        $stmt->bind_param("sdd", $name, $latitude, $longitude);
        $stmt->execute();
        $stmt->close();
        
        $groupId = $mysqli->insert_id;
        $stmt2 = $mysqli->prepare("INSERT INTO usergroups(groupid, userid, isleader) VALUES (?, ?, 1)");
        $stmt2->bind_param("ii", $groupId, $userId);
        $stmt2->execute();
        $stmt2->close();
        
        $mysqli->close();
        return $groupId;
    }
}
?>