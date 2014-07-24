<?php
error_reporting(E_ALL);
ini_set('display_errors','1');

require_once('lib/func.php');
require_once('lib/sms.php');

$json = file_get_contents('php://input');
$sms = json_decode($json);

$phone = sanitize_phone($sms['uid']);
$message = strtoupper(trim($sms['message']));

if (!already_subscribed($phone)) {
	subscribe($phone);
	send_first_message($phone);
	exit;
}

$player = getPlayer($phone);

if ($player['gender'] == '') {
	if ($message != "BOYS" || $message != "GIRLS") {
		send_error($phone);
		exit;
	}
	else {
		update_gender($phone, $message);
		if ($message == "BOYS")
			send_msg($phone, $boys);
		else
			send_msg($phone, $girls);

		update_scene($phone, "step1");
	}
	exit;
}


switch ($player['scene']) {
	case "step1":
		if ($player['gender'] == "BOYS" && $message == "CONVO") {
			send_msg($phone, $convo);
			update_scene($phone, "step2");
		}
		else if ($player['gender'] == "GIRLS" && $message == "LISTEN") {
			send_msg($phone, $listen);
			update_scene($phone, "step2");
		}
		else {
			send_error($phone);
		}
    break;

	case "step2":
		if ($player['gender'] == "BOYS" && $message == "ALEX") {
			send_msg($phone, $alex);
			update_scene($phone, "step3");
		}
		else if ($player['gender'] == "BOYS" && $message == "SAM") {
			send_msg($phone, $sam);
			update_scene($phone, "step3");
		}
		else if ($player['gender'] == "BOYS" && $message == "WALK") {
			send_msg($phone, $walk);
			update_scene($phone, "step3");
		}
		else if ($player['gender'] == "GIRLS" && $message == "STEP") {
			send_msg($phone, $step);
			update_scene($phone, "step3");
		}
		else if ($player['gender'] == "GIRLS" && $message == "TALK") {
			send_msg($phone, $talk);
			update_scene($phone, "step3");
		}
		else if ($player['gender'] == "GIRLS" && $message == "WALK") {
			send_msg($phone, $walk);
			update_scene($phone, "step3");
		}
		else {
			send_error($phone);
		}
    break;

	case "step3":
		if ($player['gender'] == "BOYS" && $message == "CAFE") {
			send_msg($phone, $end);
			update_scene($phone, "end");
		}
		else if ($player['gender'] == "BOYS" && $message == "CLASS") {
			send_msg($phone, $end);
			update_scene($phone, "end");
		}
		else if ($player['gender'] == "GIRLS" && $message == "GYM") {
			send_msg($phone, $end);
			update_scene($phone, "end");
		}
		else if ($player['gender'] == "GIRLS" && $message == "CLASS") {
			send_msg($phone, $end);
			update_scene($phone, "end");
		}
		else {
			send_error($phone);
		}
    break;

    case "end":
    	send_msg($phone, $end);
    break;
}
?>