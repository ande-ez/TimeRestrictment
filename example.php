<?php

include('TimeRestrictment.class.php');

// Database connection
$DB = new PDO('mysql:host=localhost;dbname=TimeRestrictment', 'root', '');
TimeRestrictment::setDBConnection($DB);


// Restrictment parameters
$signature 	= $_SERVER['REMOTE_ADDR'];
$type 			= TimeRestrictment::someAction;
$timeout 		= 5;


// Check if restricted
if(TimeRestrictment::restricted($type, $signature)) {
	echo('I am restricted!');
} else {
	echo('I am not restricted :)');
}


echo('<hr>');


// Restrict
TimeRestrictment::restrict($type, $signature, $timeout);


// Check if restricted
if(TimeRestrictment::restricted($type, $signature)) {
	echo('I am restricted!');
} else {
	echo('I am not restricted :)');
}


echo('<hr>');
echo('Sleeping for 6 seconds');
sleep(6);
echo('<hr>');


// Check if restricted
if(TimeRestrictment::restricted($type, $signature)) {
	echo('I am restricted!');
} else {
	echo('I am not restricted :)');
}


?>
