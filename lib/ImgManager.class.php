<?php
include 'PDO_DB_MySQL_Class/pdo_db_class.php';

class ImgManager{

	private $configs;

	private $upload_dir;

	private $mkdir_mode;

	private $error_code = 0;

	private $options = array();

	private $img = array();

	private $allowed_ext =  array();

	public function __construct(){

		$this->configs = parse_ini_file("config.ini");

		$this->upload_dir = dirname(dirname(__FILE__))  . DIRECTORY_SEPARATOR . $this->configs["upload_dir"] . DIRECTORY_SEPARATOR;

		$this->mkdir_mode = $this->configs["mkdir_mode"];

		$this->allowed_ext = explode("|", $this->configs["accept_file_types"]);

		$this->Init();

	}

	protected function Init(){
		try{

			// First check: gd extension
			if (count(gd_info()) == 0) {
				throw new Exception('Error.', 1);
			}

			if (!is_dir($this->upload_dir)) {
				mkdir($this->upload_dir, $this->configs['mkdir_mode'], true);
			} 

			// Second check: disk free space
			if(disk_free_space($this->upload_dir) < $this->configs["size_limit_alert"]){
				throw new Exception('Error.', 2);
			} 

			$this->db = new PDODB();

		} catch (Exception $e) {

		    echo json_encode(array(
		        'error' => array(
		            'msg' => $e->getMessage(),
		            'code' => $e->getCode()
		        )
		    ));
		} 
	}


	public function UploadImage(){		
		try{

			if (count($_FILES) > 0) {

				foreach ($_FILES as $key => $value) {

					$uploaded_file = $_FILES[$key]['tmp_name'];
					$uploaded_info = getimagesize($_FILES[$key]['tmp_name']);

					$this->img["type"] = image_type_to_extension($uploaded_info[2]);
					$this->img["name"] = $this->SetRandFilename();
					$this->img["orig_name"] = "orig_" . $this->img["name"] . $this->img["type"];
					$this->img["th_name"] = "th_" . $this->img["name"] . $this->img["type"];
					$this->img["md_name"] = "md_"  . $this->img["name"] . $this->img["type"];
					$this->img["size"] = filesize($uploaded_file);
					$this->img["rel_orig_path"]  = $this->configs["upload_dir"] . DIRECTORY_SEPARATOR . $this->img["orig_name"];
					$this->img["rel_th_path"] = $this->configs["upload_dir"] . DIRECTORY_SEPARATOR . $this->img["th_name"];
					$this->img["rel_md_path"] = $this->configs["upload_dir"] . DIRECTORY_SEPARATOR . $this->img["md_name"];
					$this->img["upload_type"] = (isset($_POST["submit"])) ? 'submit' : 'preview';

					if($this->img["size"] <= $this->configs["size_limit_file"]){ // check file size

						if(in_array(substr($this->img["type"], 1), $this->allowed_ext)){ // check file extension

							if (is_uploaded_file($uploaded_file)) {
						
								if (move_uploaded_file($uploaded_file, $this->img["rel_orig_path"])) {

									// set permission
									chmod($this->img["rel_orig_path"], $this->configs["file_mode"]);

									if(isset($this->configs["th_width"])){
									
										if(!isset($this->configs["th_height"])){
											$this->configs["th_height"] = $this->configs["th_width"] * $uploaded_info[1] / $uploaded_info[0];
										}

										$this->ResizeImage(array(
												"src_path" => $this->img["rel_orig_path"],
												"dst_path" => $this->img["rel_th_path"],
												"ext" => substr($this->img["type"], 1),
												"src_x" => 0,
												"src_y" => 0,
												"dst_w" =>  $this->configs["th_width"],
												"dst_h" => $this->configs["th_height"], 
												"src_w" => $uploaded_info[0],
												"src_h" => $uploaded_info[1]
											)
										);
									}
									
									if(isset($this->configs["md_width"])){

										if(!isset($this->configs["md_height"])){
											$this->configs["md_height"] = $this->configs["md_width"] *  $uploaded_info[1] / $uploaded_info[0];
										} 

										$this->ResizeImage(array(
												"src_path" => $this->img["rel_orig_path"],
												"dst_path" => $this->img["rel_md_path"],
												"ext" => substr($this->img["type"], 1),
												"src_x" => 0,
												"src_y" => 0,
												"dst_w" =>  $this->configs["md_width"],
												"dst_h" => $this->configs["md_height"], 
												"src_w" => $uploaded_info[0],
												"src_h" => $uploaded_info[1]
											)
										);
									}

									
									$file = $this->img;
									unset($this->img);

									echo json_encode(array(
								        'success' => array(
								            'data' => $file
								        )
								    ));

								} else {

									throw new Exception('Error.', 3);

								}

							} else {

								throw new Exception('Error.', 4);
							}

						} else {

							throw new Exception('Not allowed file extension.', 5);

						}

					} else {

						throw new Exception('File size is too large.', 6);

					}
				}
			} else {

				throw new Exception('File not uploaded.', 7);

			}
		} catch (Exception $e) {

		    echo json_encode(array(
		        'error' => array(
		            'msg' => $e->getMessage(),
		            'code' => $e->getCode()
		        )
		    ));
		}
	}

	private function ResizeImage($resize = array()){
		
		$src_x = ceil($resize["src_x"]);
		$src_y = ceil($resize["src_y"]);
		$dst_w = ceil($resize["dst_w"]);
		$dst_h = ceil($resize["dst_h"]);
		$src_w = ceil($resize["src_w"]);
		$src_h = ceil($resize["src_h"]);
		$dst_path = $resize["dst_path"];
		$src_path = $resize["src_path"];

		$dst_path  = ($dst_path) ? $dst_path : $src_path;
		$dst_image = imagecreatetruecolor($dst_w, $dst_h);

		switch ($resize["ext"]) {
	        case 'gif':
	            $src_image = imagecreatefromgif($src_path); 
	        break;
	        case 'jpeg':
	        case 'jpg':
	            $src_image = imagecreatefromjpeg($src_path); 
	        break;
	        case 'png':
	        	imagealphablending($dst_image, false);
				imagesavealpha($dst_image, true);  
	            $src_image = imagecreatefrompng($src_path);
	            imagealphablending($src_image, true);
	        break;
	    }

	    imagecopyresampled($dst_image, $src_image, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

		switch ($resize["ext"]) {
	         case 'gif':
	            imagegif($dst_image, $dst_path);
	        break;
	        case 'jpeg':
	        case 'jpg':
	            imagejpeg($dst_image, $dst_path);
	        break;
	        case 'png':
	            imagepng($dst_image, $dst_path);
	        break;
	    }
	}

	public function ListImage(){
		return $this->db->ExecuteQuery("SELECT * FROM gallery WHERE 1");
	}

	public function LoadImage($id){
		return $this->db->ExecuteQuery("SELECT * FROM gallery WHERE id = :id", array("id" => $id));
	}

	public function AddImage($array = array()){
		try{
			if($this->db->ExecuteQuery("INSERT INTO gallery(title, description, meta, file_name) VALUES(:title,:description,:meta,:file_name)", $array) > 0){
				echo json_encode(array(
			        'success' => array(
			            'data' => array("type" => "submit"),
			            'msg' => "Image uploaded!"
			        )
			    ));
			} else {
				throw new Exception('Error.', 8);
			}

		} catch (Exception $e) {

		    echo json_encode(array(
		        'error' => array(
		            'msg' => $e->getMessage(),
		            'code' => $e->getCode()
		        )
		    ));
		}
	}

	public function EditImage($array = array()){
		try{

			if($this->db->ExecuteQuery("UPDATE gallery SET title = :title, description = :description, meta = :meta, file_name = :file_name, status = :status WHERE id = :id", $array) > 0){
				echo json_encode(array(
				        'success' => array(
				            'data' => array(
				            	"type" => "edit",
				            	"id" => $array["id"]
				            	),
				            'msg' => "Image updated!"
				        )
				    ));
			} else {
				throw new Exception('Error.', 9);
			}

		} catch (Exception $e) {

		    echo json_encode(array(
		        'error' => array(
		            'msg' => $e->getMessage(),
		            'code' => $e->getCode()
		        )
		    ));
		}
	}

	public function RemoveImage($id){
		try{

			if($this->db->ExecuteQuery("DELETE FROM gallery WHERE id = :id", array("id" => $id)) > 0){
				echo json_encode(array(
				        'success' => array(
				            'data' => array(
				            	"type" => "remove",
				            	"id" => $id
				            	),
				            'msg' => "Image removed!"
				        )
				    ));
			} else {
				throw new Exception('Error.', 10);
			}

		} catch (Exception $e) {

		    echo json_encode(array(
		        'error' => array(
		            'msg' => $e->getMessage(),
		            'code' => $e->getCode()
		        )
		    ));
		}
	}

	public function PublishImage($id){
		try{

			if($this->db->ExecuteQuery("UPDATE gallery SET status = 1 WHERE id = :id", array("id" => $id)) > 0){
				echo json_encode(array(
				        'success' => array(
				            'data' => array(
				            	"type" => "Published",
				            	"id" => $id
				            	),
				            'msg' => "Image Published!"
				        )
				    ));
			} else {
				throw new Exception('Error.',11);
			}

		} catch (Exception $e) {

		    echo json_encode(array(
		        'error' => array(
		            'msg' => $e->getMessage(),
		            'code' => $e->getCode()
		        )
		    ));
		}
	}

	public function UnpublishImage($id){
		try{

			if($this->db->ExecuteQuery("UPDATE gallery SET status = 0 WHERE id = :id", array("id" => $id)) > 0){
				echo json_encode(array(
				        'success' => array(
				            'data' => array(
				            	"type" => "unpublished",
				            	"id" => $id
				            	),
				            'msg' => "Image unpublished!"
				        )
				    ));
			} else {
				throw new Exception('Error.', 12);
			}
		} catch (Exception $e) {

		    echo json_encode(array(
		        'error' => array(
		            'msg' => $e->getMessage(),
		            'code' => $e->getCode()
		        )
		    ));
		}
	}

	public function SetRandFilename(){
		return md5(time().rand());
	}

	private function FormatBytes($size, $precision = 0){

		// see: http://stackoverflow.com/questions/2510434/format-bytes-to-kilobytes-megabytes-gigabytes
   		$base = log($size) / log(1024);
    	$suffixes = array('', 'k', 'M', 'G', 'T');   

    	return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	}

}

?>