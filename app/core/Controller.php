<?php

namespace app\core;

use app\core\View;

abstract class Controller {

	public $route;
	public $view;
	public $acl;
	public $path;

	public function __construct($route)
	{
		$this->path = '/var/www/'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'public_html/uploads';
		$this->route = $route;
		if (!$this->checkAcl()) {
			View::errorCode(403);
		}
		$this->view = new View($route);
		$this->model = $this->loadModel($route['controller']);
	}
	public function loadModel($name)
	{
		$path = 'app\models\\'.ucfirst($name);
		if (class_exists($path)) {
			return new $path;
		}
	}
	public function checkAcl()
	{
		$this->acl = require 'app/acl/'.$this->route['controller'].'.php';
		if ($this->isAcl('all')) {
			return true;
		}
		elseif (isset($_SESSION['authorize']['id']) and $this->isAcl('authorize')) {
			return true;
		}
		return false;
	}
	public function isAcl($key)
	{
		return in_array($this->route['action'], $this->acl[$key]);
	}
	
}