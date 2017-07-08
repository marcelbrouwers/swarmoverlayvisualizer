<?php
$db = new PDO('mysql:host=localhost;dbname=databasename;charset=utf8mb4', 'databaseuser', 'databasepassword'); //Change the mysql database name, username and password
$dockerapihost = "hostname:4243"; //location of Docker Swarm API, change to yours
?>