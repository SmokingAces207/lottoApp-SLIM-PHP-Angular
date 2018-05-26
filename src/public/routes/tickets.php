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

	$number_of_lines_to_add = $request->getParam('number_of_lines');
	#$this->logger->addInfo('number of lines: ' + (int)$number_of_lines_to_add);

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
		$number_of_lines = $number_of_lines_to_add + $ticket->number_of_lines;
		#$this->logger->addInfo('number of lines: ' + (int)$number_of_lines);

		// Update current ticket information
		$stmt = $db->prepare($sql_update);
		$stmt->bindParam(':number_of_lines', $number_of_lines);
		$stmt->execute();

		$ticket_id = $ticket->ID;

		// Now we insert each line for that ticket update
		for ($i = 1; $i <= $number_of_lines_to_add; $i++) {
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
		return '{"notice": {"text": "Ticket Lines Updated"}}';

	} catch (PDOException $e) {
		return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	}
});


// Get ticket status
$app->get('/status/{id}', function (Request $request, Response $response) {

	$id = $request->getAttribute('id');

	$sql_get_lines = "SELECT * FROM ticket_lines WHERE ticket_id = $id";

	try {
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		// Get current tick information
		$stmt = $db->query($sql_get_lines);
		$ticket_lines = $stmt->fetchAll(PDO::FETCH_OBJ);

		// Check line values and calculate our result per given line
		foreach ($ticket_lines as $line) {

			$id = $line->ID;
			if ($line->num1 + $line->num2 + $line->num3 == 2) {
				#$this->logger->addInfo('Test Exit Result 10');
				$sql_update_line = "UPDATE ticket_lines SET result = '10' WHERE id = $id";

			} elseif ($line->num1 == $line->num2 && $line->num1 == $line->num3) {
				#$this->logger->addInfo('Test Exit Value 5');
				$sql_update_line = "UPDATE ticket_lines SET result = '5' WHERE id = $id";

			} elseif ($line->num1 != $line->num2 && $line->num1 != $line->num3) {
				#$this->logger->addInfo('Test Exit Value 1');
				$sql_update_line = "UPDATE ticket_lines SET result = '1' WHERE id = $id";

			} else {
				#$this->logger->addInfo('Test Exit Value 0');
				$sql_update_line = "UPDATE ticket_lines SET result = '0' WHERE id = $id";
			}

			$stmt = $db->prepare($sql_update_line);
			$stmt->execute();
		}

		$db = null;
		return '{"notice": {"text": "Status Updated"}}';

	} catch (PDOException $e) {
		return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	}
});