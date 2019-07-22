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
			'js',
		];
	}

	public function indexAction()
	{
		if(!empty($_POST)) {
			if($_POST['type'] == 'folder') {
				$path = $_POST['path'];
				$data = $this->get_data($path);
				$vars = [
					'title' => $path,
					'data' => $data,
					'path' => $path,
					'current_path' => $path,
				];
				echo $this->view->render_part( 'index', 'Файловый менеджер', $vars);
			} else if($_POST['type'] == 'file') {
				$path = $_POST['path'];
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
			} else if($_POST['type'] == 'return') {
				$path = $_POST['path'];
				$path = explode('/', $path);
				array_pop($path);
				$path = implode('/', $path);
				$data = $this->get_data($path);
				$vars = [
					'title' => $path,
					'data' => $data,
					'path' => $path,
					'current_path' => $path,
				];
				echo $this->view->render_part( 'index', 'Файловый менеджер', $vars);
			}
		} else {
			$data = $this->get_data($this->path);
			$vars = [
				'title' => $this->path,
				'data' => $data,
				'path' => $this->path,
				'current_path' => $this->path,
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
			if($val != "." && pathinfo($val, PATHINFO_EXTENSION) != "DS_Store") {
				if (empty(pathinfo($val, PATHINFO_EXTENSION))) {
					$data[$key]['name'] = '<a class="folder context" action="/" href="'.$path.'/'.$val.'">'.$val.'</a>';
					$data[$key]['size'] = count(glob($path.'/'.$val.'/*'));
					$data[$key]['format'] = 'Folder';
					$data[$key]['path_file'] = $path.'/'.$val;
				} else {
					$data[$key]['name'] = '<a class="file context" action="/" href="'.$path.'/'.$val.'">'.$val.'</a>';
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
		echo $status;
	}


	public function deleteAction()
	{
		if (!empty($_POST['href'])) {
			$status = unlink($_POST['href']);
			echo $status;
		}
	}


	public function pastAction()
	{
		if (!empty($_POST['copy_href']) && !empty($_POST['past_href']) && !empty($_POST['type'])) {
			$file = basename($_POST['copy_href']);
			if(is_dir($_POST['copy_href'])) {
				$this->copyFiles($_POST['copy_href'], $_POST['past_href']);
				$status = true;
			} else {
				$status = copy($_POST['copy_href'], $_POST['past_href'].'/'.$file);
			}
			if($_POST['type'] == 'cut') {
				unlink($_POST['copy_href']);
			}
			echo $status;
			echo true;
		}
	}


	public function copyFiles($olddirname, $newdirname){
		// если пути для копирования не существует - создаем его
		if (!is_dir($newdirname) && !file_exists($newdirname)){
			mkdir($newdirname,0777,true);
		}
            // Открываем директорию
		$dir = opendir($olddirname);
            // В цикле выводим её содержимое
		while (($file = readdir($dir)) !== false){
                // Если это файл - копируем его
			if(is_file($olddirname."/".$file)){
				copy($olddirname."/".$file, $newdirname."/".$file);
			}
                // Если это директория - создаём её
			if(is_dir($olddirname."/".$file) && $file != "." && $file != ".."){
                    // Создаём директорию
				if (!is_dir($newdirname."/".$file) && !file_exists($newdirname."/".$file)){
					mkdir($newdirname."/".$file);
				}
                    // Вызываем рекурсивно функцию copyFiles
				$this->copyFiles("$olddirname/$file", "$newdirname/$file");
			}
		}
            // Закрываем директорию
		closedir($dir);
	}


	public function testAction() {
		mkdir('/var/www/file-manager/public_html/uploads/dir', 0777, true);
	}







}