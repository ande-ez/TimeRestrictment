# Time Restrictment / Limiting Class

## Author
This repository is created by <ande@evilzone.org>

Feel free to use it as you want


## Introduction
This is a time restrictment / limiting class for limiting any sort of action.

Here are a few things I have used this for:
* Limit new use registrations for each IP by 24 hours (3600*24 seconds)
* Limit login attempts
* Limit content creation to avoid massive spam
* Limit API requests
* Limit how often to update a view counter for each IP


### Database
This class does need a database connection and one table.

#### Table
This is the neccesary table:
```SQL
CREATE TABLE TimeRestrictment (
  ID 			int NOT NULL AUTO_INCREMENT,
  type 			int NOT NULL,
  signature 	varchar(100) NOT NULL,
  regTime 		int NOT NULL,
  endTime 		int NOT NULL,
  PRIMARY KEY (ID)
)
```

#### Database connection
In the version of this class that I use in most of my projects I have a static function DB::getInstance() function which I use directly all over the place.

However, I want to make these modules as independent as possible.

This is why I have left two possible ways to get a database connection into the class.

The first way is to modify the private function 'getDBConnection' to provide your database connection.

The second way is to use the public 'setDBConnection' function to set the class local database connection.


## Usage

## Code template
```PHP
// Database connection
$DB = new PDO('mysql:host=localhost;dbname=TimeRestrictment', 'root', '');
TimeRestrictment::setDBConnection($DB);

// Restrictment parameters
$signature 	= $_SERVER['REMOTE_ADDR'];
$type 		= TimeRestrictment::someAction;
$timeout 	= 5;

// Check if restricted
if(TimeRestrictment::restricted($type, $signature)) {
	echo('I am restricted!');
} else {
	echo('I am not restricted :)');
}

// Restrict
TimeRestrictment::restrict($type, $signature, $timeout);
```


## Future plans
* Restricted counter
* Adding support for x amount of actions within a timeframe
* Signature shortcuts
