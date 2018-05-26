<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get Ticket status
$app->get('/status/{id}', function (Request $request, Response $response) {

	// $id = $request->getAttribute('id');
	
	// $sql = "SELECT * FROM tickets WHERE id = $id";

	// try {
	// 	// Get DB Object
	// 	$db = new db();
	// 	// Connect
	// 	$db = $db->connect();

	// 	$stmt = $db->query($sql);
	// 	$ticket = $stmt->fetch(PDO::FETCH_OBJ);



	// 	$db = null;
	// 	return json_encode($ticket);

	// } catch (PDOException $e) {
	// 	return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	// }
});