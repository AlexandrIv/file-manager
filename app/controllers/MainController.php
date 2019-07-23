<?php 

namespace app\controllers;

use app\core\Controller;
use app\lib\Pagination;

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
			if($val != "." && pathinfo($val, PATHINFO_EXTENSION) != "DS_Store" && $val != "..") {
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
		$this->setHistoryData('save', $_POST['path']);
		echo $status;
	}


	public function deleteAction()
	{
		if (!empty($_POST['href'])) {
			if (is_dir($_POST['href']) && file_exists($_POST['href'])){
				$status = $this->unlinkRecursive($_POST['href'], 0777);
				$this->setHistoryData('delete', $_POST['href']);
			} else {
				$status = unlink($_POST['href']);
				$this->setHistoryData('delete', $_POST['href']);
			}
			echo $status;
		}
	}

	function unlinkRecursive($dir, $deleteRootToo)
	{
		if(!$dh = @opendir($dir))
		{
			return;
		}
		while (false !== ($obj = readdir($dh)))
		{
			if($obj == '.' || $obj == '..')
			{
				continue;
			}

			if (!@unlink($dir . '/' . $obj))
			{
				unlinkRecursive($dir.'/'.$obj, true);
			}
		}
		if ($deleteRootToo)
		{
			@rmdir($dir);
		}

		return true;
	}


	public function pastAction()
	{
		if (!empty($_POST['copy_href']) && !empty($_POST['past_href']) && !empty($_POST['type'])) {
			$file = basename($_POST['copy_href']);
			if(is_dir($_POST['copy_href'])) {
				$this->copyFolder($_POST['copy_href'], $_POST['past_href']);
				$this->setHistoryData('copy-past', $_POST['copy_href']);
				$status = true;
			} else {
				$status = copy($_POST['copy_href'], $_POST['past_href'].'/'.$file);
				$this->setHistoryData('copy-past', $_POST['copy_href']);
			}
			if($_POST['type'] == 'cut') {
				unlink($_POST['copy_href']);
			}
			echo $status;
		}
	}


	public function copyFolder($olddirname, $newdirname){
		$path = explode('/', $olddirname);
		$folder = array_pop($path);
		$folderDir = $newdirname.'/'.$folder;
		if (!is_dir($folderDir) && !file_exists($folderDir)){
			chmod($newdirname, 0755);
			mkdir($folderDir, 0777, true);
		}
		$dir = opendir($olddirname);
		while (($file = readdir($dir)) !== false){
			if(is_file($olddirname."/".$file)){
				copy($olddirname."/".$file, $folderDir."/".$file);
			}
			if(is_dir($olddirname."/".$file) && $file != "." && $file != ".."){
				if (!is_dir($newdirname."/".$file) && !file_exists($newdirname."/".$file)){
					mkdir($newdirname."/".$file);
				}
				$this->copyFiles("$olddirname/$file", "$newdirname/$file");
			}
		}
		closedir($dir);
	}

	public function newAction()
	{
		if (!empty($_POST)) {
			if($_POST['type'] === 'file') {
				chmod($_POST['path'], 0777);
				$path = $_POST['path'];
				chmod($path, 0777);
				$file_path = $path.'/'.$_POST['name'].'.txt';
				fopen($file_path, 'w');
				$this->setHistoryData('new_'.$_POST['type'], $file_path);
				$status = true;
			} else if( $_POST['type'] === 'folder' ) {

				chmod($_POST['path'], 0777);
				$path = $_POST['path'];
				chmod($path, 0777);
				$folder_path = $path.'/'.$_POST['name'];
				$status = mkdir($folder_path, 0777, true);
				chmod($path, 0777);

				$this->setHistoryData('new_'.$_POST['type'], $folder_path);
			} else {
				debug($_POST);
			}
			echo $status;
		}
	}








	public function historyAction()
	{
		$count_per_page = 20;
		$pagination = new Pagination($this->route, $this->model->historyCount(), $count_per_page);
		$data = $this->model->getHistoryDb($this->route, $count_per_page);
		$vars = [
			'data' => $data,
			'pagination' => $pagination->get(),
		];
		$this->view->render('История', $vars);
	}

	public function setHistoryData($event, $path, $data = false)
	{
	
		$date = $date ?? date("Y-m-d H:i:s");
		$pathEx = explode('/', $path);
		$elem = array_pop($pathEx);
		if (is_dir($path)){
			$type = 'folder';
		} else {
			$type = 'file';
		}
		$eventVal = [];
		if(!empty($event)) {
			switch ($event) {
				case 'edit':
				$eventVal['text'] = 'Changed(edit)'.' '.$type.' '.'"'.$elem.'"'.' in';
				$eventVal['date'] = $date;
				$eventVal['elem_name'] = $elem;
				$eventVal['path'] = $path;
				break;
				case 'cut':
				$eventVal['text'] = 'Cut out'.' '.$type.' '.'"'.$elem.'"';
				$eventVal['date'] = $date;
				$eventVal['elem_name'] = $elem;
				$eventVal['path'] = $path;
				break;
				case 'copy-past':
				$eventVal['text'] = 'Copied'.' '.$type.' '.'"'.$elem.'"';
				$eventVal['date'] = $date;
				$eventVal['elem_name'] = $elem;
				$eventVal['path'] = $path;
				break;
				case 'delete':
				$eventVal['text'] = 'Deleted'.' '.$type.' '.'"'.$elem.'"';
				$eventVal['date'] = $date;
				$eventVal['elem_name'] = $elem;
				$eventVal['path'] = $path;
				break;
				case 'save':
				$eventVal['text'] = 'Edited'.' '.$type.' '.'"'.$elem.'"';
				$eventVal['date'] = $date;
				$eventVal['elem_name'] = $elem;
				$eventVal['path'] = $path;
				break;
				case 'new_folder':
				$eventVal['text'] = 'New '.$type.' created'.' '.'"'.$elem.'"';
				$eventVal['date'] = $date;
				$eventVal['elem_name'] = $elem;
				$eventVal['path'] = $path;
				break;
				case 'new_file':
				$eventVal['text'] = 'New - '.$type.' created'.' '.'"'.$elem.'"';
				$eventVal['date'] = $date;
				$eventVal['elem_name'] = $elem;
				$eventVal['path'] = $path;
				break;
				default:
				# code...
				break;
			}
		}
		$this->model->setHistoryDb($eventVal, $event, $type, $date);
	}






}