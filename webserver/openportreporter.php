<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'config.php';
$data = json_decode(json_decode(file_get_contents('php://input')), true);

$stmt = $db->prepare("INSERT INTO ports(host,container,protocol,localaddress,foreignaddress,program,timestamp) VALUES(:host,:container,:protocol,:localaddress,:foreignaddress,:program,:timestamp)");
$stmt->execute(array(':host' => $data['host'], ':container' => $data['container'], ':protocol' => $data['protocol'], ':localaddress' => $data['localaddress'], ':foreignaddress' => $data['foreignaddress'], ':program' => $data['program'], ':timestamp' => time()));
$affected_rows = $stmt->rowCount();

$stmt = $db->prepare("DELETE FROM ports WHERE container = ? AND timestamp <= ?");
$time = time() - 60;
$stmt->execute(array($data['container'], $time));
?>