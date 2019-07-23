<?php 

return [
	// Main Controller
	'' => [
		'controller' 	=> 'main',
		'action'		=> 'index',
	],

	'delete' => [
		'controller' 	=> 'main',
		'action'		=> 'delete',
	],

	'past' => [
		'controller' 	=> 'main',
		'action'		=> 'past',
	],

	'file/save' => [
		'controller' 	=> 'main',
		'action'		=> 'save',
	],

	'history' => [
		'controller' 	=> 'main',
		'action'		=> 'history',
	],

	'main/history/{page:\d+}' => [
		'controller' 	=> 'main',
		'action'		=> 'history',
	],

	'test' => [
		'controller' 	=> 'main',
		'action'		=> 'test',
	],


	'new' => [
		'controller' 	=> 'main',
		'action'		=> 'new',
	],

];