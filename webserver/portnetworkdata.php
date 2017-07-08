<?php
include_once 'config.php';
$tasks = json_decode(file_get_contents('http://'.$dockerapihost.'/tasks'));
$engines = json_decode(file_get_contents('http://'.$dockerapihost.'/nodes'));
$networks = json_decode(file_get_contents('http://'.$dockerapihost.'/networks'));
$services = json_decode(file_get_contents('http://'.$dockerapihost.'/services'));

$nodes = array();
$connections = array();
foreach ($networks as $network) {
		if($network->{'Name'}!="bridge" and $network->{'Name'}!="host" and $network->{'Name'}!="docker_gwbridge" and $network->{'Name'}!="none"){
		$arradd['id'] = $network->{'Id'};
		$arradd['group'] = $network->{'Id'};
		$arradd['name'] = $network->{'Name'};
		$arradd['size'] = 10;
		if($network->{'Attachable'}=="true"){$attachable="yes";}
		else{$attachable="no";}
		$subnet = json_decode(file_get_contents('http://'.$dockerapihost.'/networks/'.$network->{'Id'}), true)['IPAM']['Config'][0]['Subnet'];
		
		$arradd['tooltip'] = "Name: " . $network->{'Name'} . "<br />VNI: " . $network->{'Options'}->{'com.docker.network.driver.overlay.vxlanid_list'} . "<br />Attachable: " . $attachable . "<br />Subnet: " . $subnet;
		$arradd['firewall'] = "";
		$connection['source'] = $network->{'Id'};
		$connection['target'] = $network->{'Id'};
		array_push($nodes, $arradd);
		}
	
}

foreach ($tasks as $key => $task) {
	if($task->{'Status'}->{'State'}=="running"){
		
		$arradd['id'] = $task->{'Status'}->{'ContainerStatus'}->{'ContainerID'};
		$arradd['group'] = "node";
		$arradd['name'] = substr($task->{'Status'}->{'ContainerStatus'}->{'ContainerID'}, 0, 12);
		$arradd['size'] = 5;
		
		$containerid = $arradd['name'];
		$image = substr($task->{'Spec'}->{'ContainerSpec'}->{'Image'}, 0, 15);
		$pid = $task->{'Status'}->{'ContainerStatus'}->{'PID'};
		$arradd['firewall'] = "";
		foreach($db->query("SELECT * FROM firewall WHERE container='$containerid'") as $row) {
			$arradd['firewall'] = $row['inputpolicy'];
		}
		foreach ($services as $service){
			if($task->{'ServiceID'} == $service->{'ID'}){$servicename = $service->{'Spec'}->{'Name'};}
		}
		foreach($engines as $node){
			if($task->{'NodeID'} == $node->{'ID'}){		
				$residesonnode = $node->{'Description'}->{'Hostname'};
			}
		}
		
		
		$arradd['tooltip'] = "ID: " . $containerid . "<br/>Image: " . $image . "<br />PID: " . $pid . "<br />FW Input Chain: " . $arradd['firewall'] . "<br />Servicename: " . $servicename . "<br />Node: " . $residesonnode;
		array_push($nodes, $arradd);
		
		
		foreach($db->query("SELECT * FROM ports WHERE container='$containerid'") as $row) {
			$arradd['id'] = $task->{'ID'}.'.'.$row['localaddress'];
			$arradd['group'] = "port";
			$arradd['name'] = $row['localaddress'];
			$arradd['size'] = 3;
			$arradd['tooltip'] = "Program: " . $row['program'] . "<br />Protocol: " . $row['protocol'] . "<br />Local address: " . $row['localaddress'] . "<br />Foreign Address: " . $row['foreignaddress'];
			
			$connection['source'] = $task->{'Status'}->{'ContainerStatus'}->{'ContainerID'};
			$connection['target'] = $task->{'ID'}.'.'.$row['localaddress'];
			if(strpos($row['localaddress'], '127.0.0') === false){
			array_push($nodes, $arradd);
			array_push($connections, $connection);
			}
			
		}
		
		foreach ($task->{'NetworksAttachments'} as $networkattachment => $network) {
			$connection['source'] = $task->{'Status'}->{'ContainerStatus'}->{'ContainerID'};
			$connection['target'] = $network->{'Network'}->{'ID'};
			array_push($connections, $connection);
		}
	}
}

$output = array();
$output['nodes'] = $nodes;
$output['links'] = $connections;
print json_encode($output, JSON_PRETTY_PRINT);
?>
