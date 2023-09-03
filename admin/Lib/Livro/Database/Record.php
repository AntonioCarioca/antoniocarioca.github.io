<?php

	namespace Livro\Database;

	use Exception;

	abstract class Record implements RecordInterface{

		protected $data;

		public function __construct($id = null){
			if($id){
				$object = $this->load($id);
				if($object){
					$this->fromArray($object->toArray());
				}
			}
		}

		public function __set($prop, $value){
			if($value === NULL){
				unset($this->data[$prop]);
			}else{
				$this->data[$prop] = $value;
			}
		}

		public function __get($prop){
			if(isset($this->data[$prop])){
				return $this->data[$prop];
			}
		}

		public function __isset($prop){
			return isset($this->data[$prop]);
		}

		public function __clone(){
			unset($this->data['id']);
		}

		public function fromArray($data){
			$this->data = $data;
		}

		public function toArray(){
			return $this->data;
		}

		public function getEntity(){
			$class = get_class($this);
			return constant("{$class}::TABLENAME");
		}

		public function load($id){
			$sql = "SELECT * FROM {$this->getEntity()} WHERE id=" . (int) $id;

			if($conn = Transaction::get()){
				Transaction::log($sql);
				$result = $conn->query($sql);
				if($result){
					return $result->fetchObject(get_class($this));
				}
			}else{
				throw new Exception("Não há transação ativa.");
			}
		}

		public function store(){
			if(empty($this->data['id'])  or (!$this->load($this->data['id'])) ){
				$prepared = $this->prepare($this->data);

				$sql = "INSERT INTO {$this->getEntity()}" .
								'(' . implode(', ', array_keys($prepared)) . ')' .
								" VALUES " .
								'(' . implode(', ', array_values($prepared)) . ')'; 
			}else{
				$prepared = $this->prepare($this->data);
				$set = [];
				foreach($prepared as $column => $value){
					$set[] = "$column = $value";
				}

				$sql = "UPDATE {$this->getEntity()}";
				$sql .= " SET " . implode(', ', $set);
				$sql .= " WHERE id=" . (int) $this->data['id'];

			}


			if($conn = Transaction::get()){
				Transaction::log($sql);
				return $conn->exec($sql);
			}else{
				throw new Exception("Não há transação ativa.");
			}
		}

		public function delete($id = NULL){
			$id = $id ? $id : $this->data['id'];
			$sql = "DELETE FROM {$this->getEntity()} WHERE id=" . (int) $id;
			if($conn = Transaction::get()){
				Transaction::log($sql);
				return $conn->exec($sql);
			}else{
				throw new Exception("Não há transação ativa.");
			}
		}

		public static function all()
		{
			$classname = get_called_class();
      $rep = new Repository($classname);
      return $rep->load(new Criteria);
		}

		public static function find($id){
			$classname = get_called_class();
			$ar = new $classname;
			return $ar->load($id);
		}

		public function prepare($data){
			$prepared = array();
			foreach($data as $key => $value){
				if(is_scalar($value)){
					$prepared[$key] = $this->escape($value);
				}
			}
			return $prepared;
		}

		public function escape($value){
			if(is_scalar($value) and (!empty($value))){
				// adiciona \ em aspas
				$value = addslashes($value);
				return "'$value'";

			}else if(is_bool($value)){
				return $value ? 'TRUE' : 'FALSE';

			}else if($value!== ''){
				return $value;

			}else{
				return "NULL";
			}
		}

	}