<?php

	namespace Livro\Database;

	use Exception;
	use PDO;

	class Connection{

		private function __construct(){}

		public static function open($name){

			if(file_exists("App/Config/{$name}.ini")){
				$db = parse_ini_file("App/Config/{$name}.ini");
			}else{
				throw new Exception("Arquivo {$name} não encontrado");
			}

			$host = isset($db['host']) ? $db['host'] : null;
			$name = isset($db['name']) ? $db['name'] : null;
			$user = isset($db['user']) ? $db['user'] : null;
			$pass = isset($db['pass']) ? $db['pass'] : null;
			$type = isset($db['type']) ? $db['type'] : null;

			switch ($type){
				case 'pgsql':
					$port = isset($db['port']) ? $db['port'] : '5432';
					$conn = new PDO("pgsql:dbname={$name}; user={$user}; password={$pass}; host={$host}; port={$port}");
					break;
				case 'mysql':
					$port = isset($db['port']) ? $db['port'] : '3306';
					$conn = new PDO("mysql:host={$host};dbname={$name};port={$port}", "{$user}", "{$pass}");
					break;
				case 'sqlite':
					$conn = new PDO("sqlite:{$name}");
					break;
			}

			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;

		}

	}