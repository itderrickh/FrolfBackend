<?php
class UserImagesDAO {
    private $config;
    function __construct($config) {
        $this->config = $config;
    }

    function doesImageExist($userId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM userimages WHERE userid = ?");
        $stmt->bind_param("i", $userId);                          
        $stmt->execute();
        
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0;
    }

    function addDBImage($userId, $uuid) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("INSERT INTO userimages(userid, url) VALUES (?, ?)");
        
        //Make a url here
        $url = 'http//webdev.cs.uwosh.edu/students/heined50/FrolfBackend/uploads/' + $uuid + '.jpg';

        $stmt->bind_param("ii", $userId, $url);
        $stmt->execute();
        
        $stmt->close();
        $mysqli->close();
    }

    function getUserImage($userId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("SELECT userid, url FROM useriamges WHERE userid = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $stmt->bind_result($userId, $url);
        $stmt->fetch();

        $result['userid'] = $userId;
        $result['url'] = $url;

        $stmt->close();
        $mysqli->close();

        return $result;
    }
}
?>