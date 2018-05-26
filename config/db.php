<?php
	class db {
		// Properties
		private $dbhost = 'localhost';
		private $dbuser = 'root';
		private $dbpassword = '';
		private $dbname = 'lottoapp';

		// Connect to database
		public function connect() {
			$mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname";
			$dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpassword);
			$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$dbConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			return $dbConnection;
		}
	}