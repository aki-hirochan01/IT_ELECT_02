<?php  
	$server = "localhost"; //127.0.0.1
	$username = "root";
	$password = "";
	$database_name = "it_elect_02_db";

	$conn = mysqli_connect($server, $username, $password, $database_name);

	if ($conn != true) {
		echo "Database Not Connected!";
	}
	// Load DB schema from single SQL file (idempotent CREATEs)
	$schemaFile = __DIR__ . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'schema.sql';
	if (is_readable($schemaFile)) {
		$sql = file_get_contents($schemaFile);
		if ($sql !== false && trim($sql) !== '') {
			// run as multi query; CREATE TABLE IF NOT EXISTS is safe to run repeatedly
			if (mysqli_multi_query($conn, $sql)) {
				// flush any remaining results
				do {
					if ($res = mysqli_store_result($conn)) {
						mysqli_free_result($res);
					}
				} while (mysqli_more_results($conn) && mysqli_next_result($conn));
			}
		}
	}
?> 