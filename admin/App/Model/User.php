<?php

	use Livro\Database\Repository;
	use Livro\Database\Criteria;
	use Livro\Database\Record;

	class User extends Record
	{

		const TABLENAME = 'user';

		public static function findByLogin($login)
		{
			$criteria = new Criteria;
			$criteria->add('login', '=', $login);

			$repository = new Repository('user');
			$users = $repository->load($criteria);

			if( count($users) > 0 )
			{
				return $users[0];
			}

		}

		public function validatePassword($password)
		{
			return ($this->password == hash('sha256', $password) );
		}

	}