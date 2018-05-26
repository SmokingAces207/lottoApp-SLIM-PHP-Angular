<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

//Database object
require '../../config/db.php';

$app = new \Slim\App([]);

// Ticket Routes
require 'routes/tickets.php';
// Status Routes
#require 'routes/status.php';

// Slim Dependency Injection Container
$container = $app->getContainer();

// Allows html views to be displyaed by linking to our injection container
$container['view'] = new \Slim\Views\PhpRenderer('views/');

// Error Logging dependency linked to injection container
$container['logger'] = function($c) {
	$logger = new \Monolog\Logger('my_logger');
	$file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
	$logger->pushHandler($file_handler);
	return $logger;
};

// Default Home Ticket
$app->get('/', function (Request $request, Response $response) {
	
	$sql_tbl = "CREATE TABLE IF NOT EXISTS tickets(
										ID int NOT NULL AUTO_INCREMENT,
										number_of_lines int NOT NULL,
										PRIMARY KEY (ID)
									)";

	$sql_tbl2 = "CREATE TABLE IF NOT EXISTS ticket_lines(
										ID int NOT NULL AUTO_INCREMENT,
										ticket_id int NOT NULL,
										num1 int NOT NULL,
										num2 int NOT NULL,
										num3 int NOT NULL,
										result int NOT NULL,
										PRIMARY KEY (ID)
									)";

	try {
		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->prepare($sql_tbl);
		$stmt->execute();

		$stmt = $db->prepare($sql_tbl2);
		$stmt->execute();
		$db = null;

		// We serve our main home page here
		$response = $this->view->render($response, 'index.html', []);
    	return $response;

	} catch (PDOException $e) {
		return '{"error": {"error": '.$e->getMessage().' {"line": '.$e->getLine().'}}';
	}
});

//Run the app
$app->run();
