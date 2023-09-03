<?php

	class LoggerTXT extends Logger{

		public function write($message){
			$text = date('Y-m-d H:i:s') . ' : ' . $message . PHP_EOL;
			$handler = fopen($this->filename, 'a');
			fwrite($handler, $text);
			fclose($handler);
		}

	}