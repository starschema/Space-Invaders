<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/scores', 'getScores');
$app->get('/highscores', 'getHighScores');
$app->post('/addscore', 'addScore');

$app->run();

// Get All Scores
function getScores() {

	$sql = "SELECT * FROM spaceinvaders ORDER BY score DESC";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$scores = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		echo json_encode($scores);

	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

// Get High Scores
function getHighScores() {

	$sql = "SELECT * FROM spaceinvaders ORDER BY score DESC LIMIT 10";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$scores = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		echo json_encode($scores);

	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

// Record Score
function addScore() {

	$request = Slim::getInstance()->request();
	$score = json_decode($request->getBody());
	$sql = "INSERT INTO spaceinvaders (score, pseudo) VALUES (:score,:pseudo)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("pseudo", $score->pseudo);
		$stmt->bindParam("score", $score->score);
		$stmt->execute();
		$score->id = $db->lastInsertId();
		$db = null;

		echo json_encode($score);

	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getConnection() {
	$dbhost="db370296095.db.1and1.com";
	$dbuser="dbo370296095";
	$dbpass="jeneboitpl";
	$dbname="db370296095";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->exec('SET NAMES utf8');
	return $dbh;
}

?>