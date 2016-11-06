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
        $stmt->bind_result($id, $email, $friendId);
        while ($stmt->fetch()) {
            $row['id'] = $id;
            $row['email'] = $email;
            $row['friendid'] = $friendId;
            array_push($resultGroups, $row);
        }

        $stmt->close();
        $mysqli->close();

        return $resultGroups;
    }

    function addFriend($userId, $friendId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("INSERT INTO friends (dateadded, friendid, userid) VALUES (NOW(), ?, ?)");
        $stmt->bind_param("ii", $friendId, $userId);
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
        $stmt->bind_result($id, $email, $dateadded);
        while ($stmt->fetch()) {
            $row['id'] = $id;
            $row['email'] = $email;
            $row['dateadded'] = $dateadded;
            array_push($resultGroups, $row);
        }

        $stmt->close();
        $mysqli->close();

        return $resultGroups;
    }

    function getFrontPageStats($userId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("CALL getFrontPageStats(?)");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $resultStats = array();
        $stmt->bind_result($id, $datescored, $groupName, $score, $par, $holes);
        while ($stmt->fetch()) {
            $row['id'] = $id;
            $row['datescored'] = $datescored;
            $row['groupname'] = $groupName;
            $row['score'] = $score;
            $row['par'] = $par;
            $row['holes'] = $holes;
            array_push($resultStats, $row);
        }

        $stmt->close();
        $mysqli->close();

        return $resultStats;
    }
}
?>