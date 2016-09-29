<?php
date_default_timezone_set('UTC');
class GroupDAO {
    private $config;
    function __construct($config) {
        $this->config = $config;
    }
    
    function createGroup($name, $userId, $latitude, $longitude) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("INSERT INTO groups(name, datecreated, latitude, longitude, iscomplete) VALUES (?, NOW(), ?, ?, 0)");
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
    
    function getAvailableGroups() {
        $resultGroups = array();
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("SELECT groups.id, groups.name, latitude, longitude, users.email FROM groups RIGHT JOIN usergroups ON usergroups.groupid = groups.id RIGHT JOIN users ON users.id = usergroups.userid WHERE iscomplete = 0 AND DATE(NOW()) = datecreated AND usergroups.isleader");
        $stmt->execute();
        
        $stmt->bind_result($id, $name, $datecreated, $latitude, $longitude, $email);
        while ($stmt->fetch()) {
            $row['id'] = $id;
            $row['name'] = $name;
            $row['datecreated'] = $datecreated;
            $row['latitude'] = $latitude;
            $row['longitude'] = $longitude;
            $row['email'] = $email;
            array_push($resultGroups, $row);
        }

        $stmt->close();
        $mysqli->close();
        
        return $resultGroups;
    }
}
?>