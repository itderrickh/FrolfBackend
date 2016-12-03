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
        
        //Insert all scores
        $stmt3 = $mysqli->prepare("CALL insertUserScores(?, ?)");
        $stmt3->bind_param("ii", $groupId, $userId);
        $stmt3->execute();
        $stmt3->close();
        
        $mysqli->close();
        return $groupId;
    }

    function finishGame($groupId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("UPDATE groups SET iscomplete = 1 WHERE id = ?");
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
    }

    function isInGroup($groupId, $userId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM usergroups WHERE userid = ? AND groupid = ?");
        $stmt->bind_param("ii", $userId, $groupId);                          
        $stmt->execute();
        
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0;
    }

    function joinGroup($groupId, $userId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("INSERT INTO usergroups(groupid, userid, isleader) VALUES (?, ?, 0)");
        $stmt->bind_param("ii", $groupId, $userId);
        $stmt->execute();
        $stmt->close();
        
        //Insert all scores
        $stmt2 = $mysqli->prepare("CALL insertUserScores(?, ?)");
        $stmt2->bind_param("ii", $groupId, $userId);
        $stmt2->execute();
        $stmt2->close();
        
        $mysqli->close();
    }
    
    function getAvailableGroups() {
        $resultGroups = array();
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("SELECT groups.id, groups.name, groups.datecreated, latitude, longitude, users.email FROM groups RIGHT JOIN usergroups ON usergroups.groupid = groups.id RIGHT JOIN users ON users.id = usergroups.userid WHERE iscomplete = 0 AND DATE(NOW()) = datecreated AND usergroups.isleader");
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
    
    function getGroupData($groupid) {
        $resultGroups = array();
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("SELECT DISTINCT scores.id, scores.userid, scores.value, scores.holenum, scores.groupid, users.email FROM scores
                                  LEFT JOIN users ON users.id = scores.userid
                                  LEFT JOIN usergroups ON usergroups.userid = users.id
                                  LEFT JOIN groups ON groups.id = usergroups.groupid
                                  WHERE scores.groupid = ?");
        $stmt->bind_param("i", $groupid);                          
        $stmt->execute();
        
        $stmt->bind_result($id, $userid, $value, $holenum, $groupid, $email);
        while ($stmt->fetch()) {
            $row['id'] = $id;
            $row['user'] = $userid;
            $row['value'] = $value;
            $row['holeNum'] = $holenum;
            $row['groupid'] = $groupid;
            $row['email'] = $email;
            array_push($resultGroups, $row);
        }

        $stmt->close();
        $mysqli->close();
        
        return $resultGroups;
    }
}
?>