<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// We should create database on first load, check if database exists...if not create it.

// Create Ticket
$app->post('/ticket', function (Request $request, Response $response) {

	$number_of_lines = $request->getParam('number_of_lines');
	//Logging
	#$this->logger->addInfo('Number of lines: ' + (int)$number_of_lines);
	
	// First we insert our tickets information
	$sql_query = "INSERT INTO tickets (number_of_lines) VALUES (:number_of_lines)";

	try {
		// Get DB Object
		$db = new db();
		$db = $db->connect();

		$stmt = $db->prepare($sql_query);
		$stmt->bindParam(':number_of_lines', $number_of_lines);
		// We Create our our new insert into tickets
		$stmt->execute();

		// Now we get this db object in order to use its number_of_lines value
		$ticket_id = $db->lastInsertId();
		$sql_get = "SELECT * FROM tickets WHERE id = $ticket_id";
		$stmt = $db->query($sql_get);
		$ticket = $stmt->fetch(PDO::FETCH_OBJ);
		
		// Get number_of_lines from new database object
		$number_of_lines_to_write = $ticket->number_of_lines;

		$this->logger->addInfo('number_of_lines: ' + $number_of_lines_to_write);

		// $this->logger->addInfo('Executed this far...');
		// $this->logger->addInfo('ticket_id: ' + (int)$number_of_lines);
		#$ticket_id = $db->lastInsertId();
		// $this->logger->addInfo('ticket_id: ' + (int)$ticket_id);
		// Now we insert each line for that ticket
		for ($i = 1; $i <= $number_of_lines_to_write; $i++) {
			$num1 = rand(0, 2);
			$num2 = rand(0, 2);
			$num3 = rand(0, 2);

			$sql_query2 = "INSERT INTO ticket_lines (ticket_id, num1, num2, num3) 
							VALUES (:ticket_id, :num1, :num2, :num3)";

			$stmt = $db->prepare($sql_query2);

			$stmt->bindParam(':ticket_id', $ticket_id);
			$stmt->bindParam(':num1', $num2);
			$stmt->bindParam(':num2', $num2);
			$stmt->bindParam(':num3', $num3);
			$stmt->execute();
		}

		$db = null;
		return '{"notice": {"Response": '.$number_of_lines_to_write.' Tickets Added yo"}}';

	} catch (PDOException $e) {
		return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	} catch (Exception $e) {
		return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	}
});

// Get All Tickets
$app->get('/ticket', function (Request $request, Response $response) {

	$sql = "SELECT * FROM tickets ORDER BY id";

	try {
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		// Creating a statement here is unecessary but it allows us to resuse it again, and means it is only parsed once.
		$stmt = $db->query($sql);
		$tickets = $stmt->fetchAll(PDO::FETCH_OBJ);

		$db = null;
		return  json_encode($tickets);

	} catch (PDOException $e) {
		return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	}
});

// Get Single Ticket
$app->get('/ticket/{id}', function (Request $request, Response $response) {

	$id = $request->getAttribute('id');
	
	$sql = "SELECT * FROM tickets WHERE id = $id";

	try {
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->query($sql);
		$ticket = $stmt->fetch(PDO::FETCH_OBJ);

		$db = null;
		return json_encode($ticket);

	} catch (PDOException $e) {
		return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	}
});

// Update Ticket
$app->put('/ticket/{id}', function (Request $request, Response $response) {

	$id = $request->getAttribute('id');

	$number_of_lines = $request->getParam('number_of_lines');

	$sql_get = "SELECT * FROM tickets WHERE id = $id";
	
	$sql_update = "UPDATE tickets SET number_of_lines = :number_of_lines WHERE id = $id";

	try {
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		// Get current tick information
		$stmt = $db->query($sql_get);
		$ticket = $stmt->fetch(PDO::FETCH_OBJ);

		// Add previous database value with new value
		$number_of_lines = $number_of_lines + $ticket->number_of_lines;

		// Update current ticket information
		$stmt = $db->prepare($sql_update);
		$db = null;

		$stmt->bindParam(':number_of_lines', $number_of_lines);
		$stmt->execute();

		return '{"notice": {"text": "Ticket Lines Updated"}}';

	} catch (PDOException $e) {
		return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	}
});


// Get ticket status
$app->get('/status/{id}', function (Request $request, Response $response) {

	$id = $request->getAttribute('id');

	$sql_get = "SELECT * FROM tickets WHERE id = $id";

	try {
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		// Get current tick information
		$stmt = $db->query($sql_get);
		$ticket = $stmt->fetch(PDO::FETCH_OBJ);

		// Add previous database value with new value
		$number_of_lines = $number_of_lines + $ticket->number_of_lines;

		// Update current ticket information
		$stmt = $db->prepare($sql_update);
		$db = null;

		$stmt->bindParam(':number_of_lines', $number_of_lines);
		$stmt->execute();

		return '{"notice": {"text": "Ticket Lines Updated"}}';

	} catch (Exception $e) {
		return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	}
});