<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'config.php';
$data = json_decode(json_decode(file_get_contents('php://input')), true);

$stmt = $db->prepare("INSERT INTO firewall(host,container,inputpolicy,timestamp) VALUES(:host,:container,:inputpolicy,:timestamp)");
$stmt->execute(array(':host' => $data['host'], ':container' => $data['container'], ':inputpolicy' => $data['inputpolicy'], ':timestamp' => time()));
$affected_rows = $stmt->rowCount();

$stmt = $db->prepare("DELETE FROM firewall WHERE container = ? AND timestamp <= ?");
$time = time() - 60;
$stmt->execute(array($data['container'], $time));
?>