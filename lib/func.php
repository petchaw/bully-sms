<?php
require_once('config.php');

function dbconnect() {
	$conn = new mysqli(hostname, username, password, database);
	if (!$conn) { die('Connexion to the database has failed: ' . mysqli_connect_error()); }
	return $conn;
}

function getPlayer($phone) {
	$conn = dbconnect();
	$sql = "SELECT id, phone, gender, scene FROM players WHERE phone = ".mysql_real_escape_string($phone)." LIMIT 1";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$conn->close();
	return $row;
}

function already_subscribed($phone) {
	$conn = dbconnect();
	$result = $conn->query("select phone from players where phone = '" . mysql_real_escape_string($phone) . "'");
	$conn->close();
	if ($result->num_rows == 0) return false;
	else return true;
}

function sanitize_phone($phone) {
	$charstostrip = array('-', ' ', '+', '(', ')');
	$phonenbr = str_replace($charstostrip, '', $phone);
	return $phonenbr;
}

function send_first_message($phone) {
	$message = "You arrive at school and get ur schedule. AH! Gym first period! Head to the locker room to get ready. Do you use the BOYS or GIRLS locker room?";
	send_msg($phone, $message);
}

function send_error($phone) {
	$message = "Invalid keyword, you have to text one of the words in all caps as your choice";
	send_msg($phone, $message);
}

function send_msg($to, $message) {
	$sms = array();
	$sms['to'] = $to;
	$sms['message'] = $message;
	$response = json_encode($sms);
    echo $response;
    return 1;
}

function update_gender($phone, $gender) {
	$conn = dbconnect();
	$result = $conn->query("UPDATE players SET gender = '" . mysql_real_escape_string($gender) . "' WHERE phone = " . mysql_real_escape_string($phone));
	$conn->close();
	if ($result) return true;
	else return false;
}

function update_scene($phone, $scene) {
	$conn = dbconnect();
	$result = $conn->query("UPDATE players SET scene = '" . mysql_real_escape_string($scene) . "' WHERE phone = " . mysql_real_escape_string($phone));
	$conn->close();
	if ($result) return true;
	else return false;
}

function subscribe($phone) {
	$conn = dbconnect();
	$result = $conn->query("INSERT INTO players (phone, gender, scene) VALUES (" . mysql_real_escape_string($phone) . ", '', '')");
	$conn->close();
	if ($result) return true;
	else return false;
}
?>