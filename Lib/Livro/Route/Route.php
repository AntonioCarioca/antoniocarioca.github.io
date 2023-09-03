<?php

	namespace Livro\Route;

	use Exception;

	class Route
	{

		private $actions;
		private $exception;

		public function __construct()
		{
			$this->actions 	 = [];
			$this->exception = [];
		}

		/**
    * Register an action
    * 
    * @param $action entry point
    * @param $callback response callback
    */
		public function on($action, $callback)
		{
			$this->actions[$action] = $callback;
		}

		/**
    * Execute an action
    * 
    * @param $action entry point
    * @param $args arguments
    */
		public function exec($action, $args)
		{
			call_user_func($this->actions[$action], $args);
		}

		/**
    * Register an exception
    * 
    * @param $callback response callback
    */
		public function exception($callback)
		{
			$this->exception = $callback;
		}

		/**
    * Execute the current URL action
    */
		public function run()
		{
			$args = $_REQUEST;

			if( isset($args['action']) )
			{
				$callback = $this->actions[ $args['action'] ];
			}
			else
			{
				$callback = $this->actions[''];
			}

			if( is_callable($callback) )
			{

				try
				{
					call_user_func($callback, $args, $this);
				}
				catch(Exception $e)
				{

					if( is_callable($this->exception) )
					{
						call_user_func($this->exception, $e);
					}

				}

			}

		}
		
	}