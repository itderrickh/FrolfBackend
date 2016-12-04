<?php
class FriendsDAO {
    private $config;
    function __construct($config) {
        $this->config = $config;
    }

    function getRecentGroupUsers($userId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);

        $stmt = $mysqli->prepare("SELECT DISTINCT users.id, users.email FROM groups
                                  LEFT JOIN usergroups ON usergroups.groupid = groups.id
                                  LEFT JOIN users ON usergroups.userid = users.id
                                  WHERE users.id NOT IN (SELECT friendid FROM friends WHERE userid = ?)
                                  AND users.id <> ?");
        $stmt->bind_param("ii", $userId, $userId);

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
        $stmt->bind_param("ii", $friendId, $userId);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();
    }

    function getFriends($userId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("SELECT friendid, email, dateadded, IF(MAX(playing) = 1, 1, 0) AS playing FROM (SELECT DISTINCT friends.friendid, email, friends.dateadded, 1 AS playing FROM groups
                                    LEFT JOIN usergroups ON usergroups.groupid = groups.id
                                    LEFT JOIN users ON usergroups.userid = users.id
                                    LEFT JOIN friends ON friends.friendid = users.id
                                    WHERE iscomplete = 0 AND friends.userid = ? AND DATE(datecreated) = DATE(NOW())
                                    UNION
                                    SELECT friendUser.id, friendUser.email, friends.dateadded, 0 AS playing FROM friends
                                    LEFT JOIN users AS friendUser ON friendUser.id = friendid
                                    WHERE friends.userid = ?) AS InnerGroup
                                    GROUP BY InnerGroup.email");
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();

        $resultGroups = array();
        $stmt->bind_result($id, $email, $dateadded, $isplaying);
        while ($stmt->fetch()) {
            $row['id'] = $id;
            $row['email'] = $email;
            $row['dateadded'] = $dateadded;
            $row['isplaying'] = $isplaying;
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
        $stmt->bind_result($email, $id, $datescored, $groupName, $score, $par, $holes);
        while ($stmt->fetch()) {
            $row['email'] = $email;
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