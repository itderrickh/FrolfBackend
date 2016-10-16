<?php
class ScoreDAO {
    private $config;
    function __construct($config) {
        $this->config = $config;
    }
    
    function updateScore($score, $scoreId) {
        $mysqli = new mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['dbpass'], $this->config['dbdatabase']);
        $stmt = $mysqli->prepare("UPDATE scores SET value = ? WHERE id = ?");
        $stmt->bind_param("ii", $score, $scoreId);
        $stmt->execute();
        
        $stmt->close();
        $mysqli->close();
    }
}
?>