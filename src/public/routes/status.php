<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get ticket status
$app->get('/status/{id}', function (Request $request, Response $response) {

	$id = $request->getAttribute('id');
	// $this->logger->addInfo($id);

	$sql_get_lines = "SELECT * FROM ticket_lines WHERE ticket_id = $id";

	try {
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		// Get current tick information
		$stmt = $db->query($sql_get_lines);
		$ticket_lines = $stmt->fetchAll(PDO::FETCH_OBJ);
		// $this->logger->addInfo($ticket_lines);

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

			// We have to get lines again now with updated information
			$stmt = $db->query($sql_get_lines);
			$ticket_lines = $stmt->fetchAll(PDO::FETCH_OBJ);
		}

		$db = null;
		return json_encode($ticket_lines);

	} catch (PDOException $e) {
		return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	}
});