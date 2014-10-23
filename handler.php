<?php
header('Content-Type: application/json');
session_start();
include 'lib/ImgManager.class.php';


$pct = new ImgManager();

if(!isset($_POST["pctname"]) && empty($_POST["pctname"]) && !isset($_POST["submit"])){

	$pct->UploadImage();  //upload image

} else if(isset($_POST["title"]) && !empty($_POST["title"]) && isset($_POST["submit"])){

	$img = array(
		"title"=> $_POST["title"],
		"description"=> trim($_POST["description"]),
		"meta" => trim($_POST["meta"]),
		"file_name"=> $_POST["pctname"]
	);


	if(isset($_POST["id"])){ $img["id"] = (int)$_POST["id"]; }

	if($_POST["submit"] == "add"){

		$pct->AddImage($img); // add img

	} else if($_POST["submit"] == "edit"){

		$img["status"] = (isset($_POST["status"]) ?  1 :  0);
		$pct->EditImage($img); // edit image informations

	} 
	
} else if($_POST["submit"] == "remove"){

	if(isset($_POST["id"])){ $pct->RemoveImage((int)$_POST["id"]); }// remove image 

} else if($_POST["submit"] == "publish"){

	if(isset($_POST["id"])){ $pct->PublishImage((int)$_POST["id"]); }// remove image 

} else if($_POST["submit"] == "unpublish"){

	if(isset($_POST["id"])){ $pct->UnpublishImage((int)$_POST["id"]); }// remove image 

} 

?>