<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * API
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
require_once("libraries".DS."phpFlickr.php");

class flickr{
	
	public function __construct($config) 
	{	
		if (session_id() == "") {
			@session_start();
		}
	
		$this->flick = new phpFlickr($config['appId'], $config['secret']);
	}
	
	function login()
	{
		if(!$_SESSION['user_flickr_id']){
			if (empty($_GET['frob'])) 
			{
				$this->flick->auth("read");
			} 
			else 
			{
				$user = $this->flick->auth_getToken($_GET['frob']);
				$_SESSION['user_flickr_id'] = $user['user']['nsid'];
			}
			$this->flick->auth_getToken($_GET['frob']);
			return $user['user']['nsid'];
		}else{
			return $_SESSION['user_flickr_id'];
		}
	}
	
	//get all public photos. return array(info photo);
	function getAllPhoto($user_id)
	{
		if($user_id != '')
		{
			$photos = $this->flick->people_getPublicPhotos($user_id, NULL, NULL, 21, $page);
			return $this->getPhoto($photos['photos']['photo']);
		}
	}
	
	//get photo in public photo. return array(photo + photo_big + title) for image;
	function getPhoto($photos){
		if(is_array($photos)){
			$data = array();
			foreach ($photos as $key=>$photo) 
			{
				$data[$key]['photo'] = "https://farm".$photo['farm'].".staticflickr.com/".$photo['server']."/".$photo['id']."_".$photo['secret']."_m.jpg";
				$data[$key]['photo_big'] = "https://farm".$photo['farm'].".staticflickr.com/".$photo['server']."/".$photo['id']."_".$photo['secret']."_b.jpg";
				$data[$key]['title'] = $photo['title'];
			}
			return $data;
		}
	}
	
	//get all album. return array(cover_photo + title + description + album_id + count) for album;
	function getALLAlbum(){
		if($_SESSION['user_flickr_id'] != ''){
			$photos = $this->flick->photosets_getList($_SESSION['user_flickr_id'], NULL, NULL);
			$data = array();
			foreach($photos['photoset'] as $key=>$photo){
				$data[$key]['cover_photo'] = "https://farm".$photo['farm'].".staticflickr.com/".$photo['server']."/".$photo['primary']."_".$photo['secret']."_m.jpg";
				$data[$key]['title'] = $photo['title']['_content'];
				$data[$key]['description'] = $photo['description']['_content'];
				$data[$key]['album_id'] = $photo['id'];
				$data[$key]['count'] = $photo['photos'];
			}
			return $data;
		}
	}
	
	//get photo in album photo. return array(photo + photo_big + title) for image;
	function getAlbumPhoto($album_id){
		$photos = $this->flick->photosets_getPhotos($album_id);
		$data = array();
		foreach($photos['photoset']['photo'] as $key=>$photo){
			$data[$key]['photo'] = "https://farm".$photo['farm'].".staticflickr.com/".$photo['server']."/".$photo['id']."_".$photo['secret']."_m.jpg";
			$data[$key]['photo_big'] = "https://farm".$photo['farm'].".staticflickr.com/".$photo['server']."/".$photo['id']."_".$photo['secret']."_b.jpg";
			$data[$key]['title'] = $photo['title'];
		}
		return $data;
	}
}

?>
