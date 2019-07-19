<?php 

namespace app\controllers;

use app\core\Controller;

/**
 * Main Controller
 */
class MainController extends Controller
{

	public function __construct($route)
	{
		parent::__construct($route);
	}

	public function formatImage()
	{
		return [
			'gif',
			'jpg',
			'png',
		];
	}

	public function formatText()
	{
		return [
			'txt',
			'html',
			'css',
			'php',
		];
	}

	public function indexAction()
	{
		if(!empty($_POST['path'])) {
			$path = $_POST['path'];
			$path = explode('/', $path);
			array_pop($path);
			$path = implode('/', $path);
			$data = $this->get_data($path);
			$vars = [
				'title' => $path,
				'data' => $data,
				'path' => $path,
			];
			echo $this->view->render_part( 'index', 'Файловый менеджер', $vars);
		}
		if(!empty($_POST['folder'])) {
			$path = $_POST['folder'];
			$data = $this->get_data($path);
			$vars = [
				'title' => $path,
				'data' => $data,
				'path' => $path,
			];
			echo $this->view->render_part( 'index', 'Файловый менеджер', $vars);
		} else if( !empty($_POST['file']) ) {
			$path = $_POST['file'];
			$format = pathinfo($path, PATHINFO_EXTENSION);
			$vars = [
				'title' => basename($path),
				'file' => $path,
				'format' => $format,
				'image' => $this->formatImage(),
				'text' => $this->formatText(),
				'path' => $path,
			];
			echo $this->view->render_part( 'file', 'Файловый менеджер', $vars);
		} else {
			$data = $this->get_data($this->path);
			$vars = [
				'title' => $this->path,
				'data' => $data,
				'path' => $this->path,
			];
			$this->view->render('Файловый менеджер', $vars);
		}
	}

	private function file_size($size) {
		$a = array("B", "KB", "MB", "GB", "TB", "PB");
		$pos = 0;
		while ($size >= 1024) {
			$size /= 1024;
			$pos++;
		}
		return round($size,2)." ".$a[$pos];
	}


	public function get_data($path)
	{
		$files = scandir($path, 1);
		$data = [];
		foreach ($files as $key => $val) {
			if($val != ".") {
				if (empty(pathinfo($val, PATHINFO_EXTENSION))) {
					$data[$key]['name'] = '<a class="folder" action="/" href="'.$path.'/'.$val.'">'.$val.'</a>';
					$data[$key]['size'] = count(glob($path.'/'.$val.'/*'));
					$data[$key]['format'] = 'Folder';
					$data[$key]['path_file'] = $path.'/'.$val;
				} else {
					$data[$key]['name'] = '<a class="file" action="/" href="'.$path.'/'.$val.'">'.$val.'</a>';
					$data[$key]['size'] = $this->file_size(filesize($path.'/'.$val));
					$data[$key]['format'] = pathinfo($val, PATHINFO_EXTENSION);
					$data[$key]['path_file'] = $path.'/'.$val;
				}
				$data[$key]['time'] = date('m/d/Y h:i:s', stat($path.'/'.$val)['mtime']);
			}
		}
		$price = array_column($data, 'format');
		array_multisort($price, SORT_ASC, $data);
		return $data;
	}


	public function saveAction()
	{
		$path = $_POST['path'];
		$content = $_POST['content'];
		$status = file_put_contents($path, $content);
		if ($status) {
			$this->view->message('success', 'Фаил сохранен');
		}
	}




}