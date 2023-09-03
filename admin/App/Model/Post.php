<?php

	use Livro\Database\Record;
	use Livro\Database\Repository;
	use Livro\Database\Criteria;

	class Post extends Record
	{

		const TABLENAME = 'post';

		public static function getbyTag($tag)
		{
			$repository = new Repository('Post');
			$criteria 	= new Criteria;

			if( !empty($tag) )
			{
				$criteria->add('tag', '=', $tag);
			}

			return $repository->load($criteria);

		}

	}