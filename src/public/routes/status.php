<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get Single Ticket
$app->get('/status/{id}', function (Request $request, Response $response) {

	$id = $request->getAttribute('id');

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