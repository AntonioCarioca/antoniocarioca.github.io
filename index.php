<?php
	
	require_once __DIR__ . '/vendor/autoload.php';
	require_once 'Lib/Livro/Route/Route.php';

	use Livro\Route\Route;
	use Twig\Loader\FilesystemLoader;
	use Twig\Environment;

	$route = new Route;

	// default action
	$route->on('', function($args, $route)
	{
    $route->exec('show_category', [] );
	});

	// render a category
	$route->on('show_category', function($args)
	{
		$loader = new FilesystemLoader('template');
		$twig = new Environment($loader);
		$template = $twig->load('index.html');
	
		chdir ('admin');
		require_once 'init.php';
		Livro\Database\Transaction::open('cms');
		$posts = Post::getByTag( isset($args['tag']) ? $args['tag'] : null );
		Livro\Database\Transaction::close();
	
		$replaces = array();
		$replaces['base_url'] = 'template';
	
		foreach ($posts as $post)
		{
	    $replaces['posts'][] = $post->toArray();
		}
	
		$content = $template->render($replaces);
    echo $content;
	});

	// render a post
	$route->on('show_post', function($args)
	{
		$loader = new FilesystemLoader('template');
		$twig = new Environment($loader);
		$template = $twig->load('post.html');
	
		if (!empty($args['id']))
		{
    	chdir ('admin');
    	require_once 'init.php';
    	Livro\Database\Transaction::open('cms');
    	$post = new Post($args['id']);
    	Livro\Database\Transaction::close();
    	
    	$replaces = $post->toArray();
    	$replaces['base_url'] = 'template';
    	$content = $template->render($replaces);
      echo $content;
    }
	});

	// in case of exception
	$route->exception( function($exception)
	{
		$loader = new FilesystemLoader('template');
		$twig = new Environment($loader);
		$template = $twig->load('post.html');
	
		$replaces = array();
		$replaces['base_url'] = 'template';
		$replaces['title'] = $exception->getMessage();
	
		$content = $template->render($replaces);
    echo $content;
	});

	$route->on('teste', function ($args) 
	{
  	print 'teste';
    print '<br>';
    var_dump($args);
	});

	$route->run();