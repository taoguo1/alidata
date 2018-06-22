<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Aliyun\Oss;

class Upload extends Controller {
	public function index($id = '', $path = '') {
		$this->assign ( 'id', $id );
		$this->assign ( 'path', $path );
		$this->view ();
		$act = Lib::post ( 'act' );
		if($act == 'upload'){
            /* 将文件上传的信息取出赋给变量 */
            $name = @$_FILES['file_name']['name'];
            $tmp_name = @$_FILES['file_name']['tmp_name'];
            $size = @$_FILES['file_name']['size'];
            $error = @$_FILES['file_name']['error'];

            $aryStr = explode ( ".", $name );
            $fileType = $aryStr [count ( $aryStr ) - 1];
            $newFileName = 'backend_'.$id.'_'.date ( 'YmdHis' ) . "_" . rand ( 100000, 999999 ) . "-oss.".$fileType;
            $oss = Oss::instance();
            $ret = $oss->uploadOss($newFileName,$tmp_name);
            $errors = "错误！";

            if ($ret) {
                echo '<script>setImgPath("' . $newFileName . '");</script>';
            } else {
                echo '<script>alert("'.$errors.$size.$error.'");history.go(-1);</script>';
            }
        }

        /*
		if ($act == 'upload') {
			$up = new \Core\Upload ();
			if ($path) {
				$path = $path . "/";
			}
			$path = 'Public/Uploads/' . $path;
			$up->set ( "path", APP_PATH . $path );
			$up->set ( "maxsize", 2000000 );
			$up->set ( "allowtype", array (
					"gif",
					"png",
					"jpg",
					"jpeg" 
			) );
			if ($up->upload ( "file_name" )) {
			    $path = \str_replace('Public/','',$path);
				$pathFile = $path . $up->getFileName ();
				echo '<script>setImgPath("' . $pathFile . '");</script>';
			} else {
				echo '<script>alert("' . $up->getErrorMsg () . '");history.go(-1);</script>';
			}
		}
        */
	}
	/**
	 *
	 * @name 编辑器图片上传
	 */
	public function uploadEditor($path = '') {
	    $up = new \Core\Upload ();
		if ($path) {
			$path = $path . "/";
		}
		$path = 'Public/Uploads/' . $path;
		$up->set ( "path", APP_PATH . $path );
		$up->set ( "maxsize", 2000000 );
		$up->set ( "allowtype", array (
				"gif",
				"png",
				"jpg",
				"jpeg" 
		) );
		if ($up->upload ( "filedata" )) {
		    $path = \str_replace('Public/','',$path);
			$pathFile = $path . $up->getFileName ();
			$pathFile = APP_SITE_PATH . $pathFile;
			$array = array (
					"err" => "",
					"msg" => $pathFile,
					"url" => $pathFile 
			);
		} else {
			$array = array (
					"err" => $up->getErrorMsg (),
					"msg" => "请选择要上传的Swf" 
			);
		}
		echo json_encode ( $array );
	}
	/**
	 *
	 * @name 编辑器Media上传
	 */
	public function uploadEditorMedia($path = '') {
	    $up = new \Core\Upload ();
		if ($path) {
			$path = $path . "/";
		}
		$path = 'Public/Uploads/' . $path;
		$up->set ( "path", APP_PATH . $path );
	//	$up->set ( "maxsize", 20000000 );
		$up->set ( "allowtype", array (
				"avi",
				"mp4" 
		) );
		if ($up->upload ( "filedata" )) {
		    $path = \str_replace('Public/','',$path);
			$pathFile = $path . $up->getFileName ();
			$pathFile = APP_SITE_PATH . $pathFile;
			$array = array (
					"err" => "",
					"msg" => $pathFile,
					"url" => $pathFile 
			);
		} else {
			$array = array (
					"err" => $up->getErrorMsg (),
					"msg" => "请选择要上传的视频" 
			);
		}
		echo json_encode ( $array );
	}
	
	/**
	 *
	 * @name 编辑器Swf上传
	 */
	public function uploadEditorSwf($path = '') {
	    $up = new \Core\Upload ();
		if ($path) {
			$path = $path . "/";
		}
		$path = 'Public/Uploads/' . $path;
		$up->set ( "path", APP_PATH . $path );
//		$up->set ( "maxsize", 2000000 );
		$up->set ( "allowtype", array (
				"swf" 
		) );
		if ($up->upload ( "filedata" )) {
		    $path = \str_replace('Public/','',$path);
			$pathFile = $path . $up->getFileName ();
			$pathFile = APP_SITE_PATH . $pathFile;
			$array = array (
					"err" => "",
					"msg" => $pathFile,
					"url" => $pathFile 
			);
		} else {
			$array = array (
					"err" => $up->getErrorMsg (),
					"msg" => "请选择要上传的图片" 
			);
		}
		echo json_encode ( $array );
	}
	/**
	 *
	 * @name 编辑器File上传
	 */
	public function uploadEditorFile($path = '') {
	    $up = new \Core\Upload ();
		if ($path) {
			$path = $path . "/";
		}
		$path = 'Public/Uploads/' . $path;
		$up->set ( "path", APP_PATH . $path );
//		$up->set ( "maxsize", 2000000 );
		$up->set ( "allowtype", array (
				"zip",
				"rar",
				"txt",
				"pdf",
				"ppt",
				"doc",
				"xls",
				"pptx",
				"docx",
				"xlsx"
		) );
		if ($up->upload ( "filedata" )) {
		    $path = \str_replace('Public/','',$path);
			$pathFile = $path . $up->getFileName ();
			$pathFile = APP_SITE_PATH . $pathFile;
			$array = array (
					"err" => "",
					"msg" => $pathFile,
					"url" => $pathFile
			);
		} else {
			$array = array (
					"err" => $up->getErrorMsg (),
					"msg" => "请选择要上传的图片"
			);
		}
		echo json_encode ( $array );
	}
	
}