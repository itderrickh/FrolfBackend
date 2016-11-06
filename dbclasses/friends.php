<?php
class FriendsDAO {
    private $config;
    function __construct($config) {
        $this->config = $config;
    }

    function getRecentGroupUsers($userId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("SELECT DISTINCT users.id, users.email, friendid FROM groups
                                  LEFT JOIN usergroups ON usergroups.groupid = groups.id
                                  LEFT JOIN users ON usergroups.userid = users.id
                                  LEFT JOIN friends ON friends.userid = users.id
                                  WHERE users.id <> ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $resultGroups = array();
        $stmt->bind_result($id, $email);
        while ($stmt->fetch()) {
            $row['id'] = $id;
            $row['email'] = $email;
            array_push($resultGroups, $row);
        }

        $stmt->close();
        $mysqli->close();

        return $resultGroups;
    }

    function addFriend($userId, $friendId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("INSERT INTO friends (dateadded, friendid, userid) VALUES (NOW(), ?, ?)");
        $stmt->bind_param("i", $friendId, $userId);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }

    function getFriends($userId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("SELECT friendUser.id, friendUser.email, friends.dateadded FROM friends
                                  LEFT JOIN users AS friendUser ON friendUser.id = friendid
                                  WHERE friends.userid = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $resultGroups = array();
        $stmt->bind_result($id, $email);
        while ($stmt->fetch()) {
            $row['id'] = $id;
            $row['email'] = $email;
            array_push($resultGroups, $row);
        }

        $stmt->close();
        $mysqli->close();

        return $resultGroups;
    }
}
?>