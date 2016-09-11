<?php

/**
 * Class for restricting generic actions by time
 *
 */
class TimeRestrictment {

	/**
	 * Predefined restrictment types
	 */
	const someAction = 1;

	/**
	 * Database connection
	 */
	private static $_connection = NULL;


	// ---------------------------------------------------------------------------
	/**
	 * Checks if a action is restricted
	 *
	 */
	public static function restricted($type, $signature) {
		// Get DB connection
		$DBInterface = self::getDBConnection();

		$statement = $DBInterface->prepare('SELECT * FROM TimeRestrictment WHERE type=:type AND signature=:signature');
		$statement->bindParam(':type', 		$type);
		$statement->bindParam(':signature', $signature);
		$statement->execute();

		if($statement->rowCount() == 0) {
			return FALSE;
		}

		if($statement->rowCount() == 1) {
			// We have a restrictment match, but is it active?
			$restrictment = $statement->fetch();
			if($restrictment['endTime'] < time()) {
				return FALSE;
			}
		}

		return TRUE;
	}


	// ---------------------------------------------------------------------------
	/**
	 * Restricts a action
	 *
	 */
	public static function restrict($type, $signature, $timeout) {
		// Get DB connection
		$DBInterface = self::getDBConnection();

		// Check if we are already restricted
		$statement = $DBInterface->prepare('SELECT * FROM TimeRestrictment WHERE type=:type AND signature=:signature');
		$statement->bindParam(':type', 		$type);
		$statement->bindParam(':signature', $signature);
		$statement->execute();

		if($statement->rowCount() == 0) {

			$statement = $DBInterface->prepare('INSERT INTO TimeRestrictment SET type=:type, signature=:signature, regTime=:regTime, endTime=:endTime');

		} else {

			// If we are already restricted, dont update the timestamps
			$restrictment = $statement->fetch();
			if($restrictment['endTime'] > time()) {
				return;
			}

			$statement = $DBInterface->prepare('UPDATE TimeRestrictment SET regTime=:regTime, endTime=:endTime WHERE type=:type AND signature=:signature');

		}

		// Setup the values
		$regTime = time();
		$endTime = $regTime + $timeout;

		// Bind the values
		$statement->bindParam(':type', $type);
		$statement->bindParam(':signature', $signature);
		$statement->bindParam(':regTime', $regTime);
		$statement->bindParam(':endTime', $endTime);

		// Execute the query
		$statement->execute();
	}


	// ---------------------------------------------------------------------------
	/**
	 * Removes a restriction on a action
	 *
	 */
	public static function unRestrict($type, $identifier) {
		$DBInterface = self::getDBConnection();

		$statement = $DBInterface->prepare('DELETE FROM TimeRestrictment WHERE type=:type AND signature=:signature');
		$statement->bindParam(':type', 		$type);
		$statement->bindParam(':signature', $signature);
		$statement->execute();
	}


	// ---------------------------------------------------------------------------
	/**
	 * Removes a restriction on a action
	 *
	 */
	public static function getDBConnection() {
		if(self::$_connection == NULL) {
			throw new Exception('Database connection not set');
		}
		return self::$_connection;
	}


	// ---------------------------------------------------------------------------
	/**
	 * Sets the local database connection
	 *
	 */
	public static function setDBConnection($connection) {
		self::$_connection = $connection;
	}

}
