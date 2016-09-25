<?php
class UserGroupDAO {
    private $config;
    function __construct($config) {
        $this->config = $config;
    }
    
    function addUserToGroup($groupId, $userId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("INSERT INTO usergroups(userid, groupid) VALUES (?, ?, 0)");
        
        $stmt->bind_param("ii", $userId, $groupId);
        $stmt->execute();
        
        $stmt->close();
        $mysqli->close();
    }
}
?>