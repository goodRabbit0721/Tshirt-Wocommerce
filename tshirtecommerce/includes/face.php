<?php
require_once('facebook'.DS.'autoload.php');
require_once(ROOT.DS."includes".DS."functions.php");

class Face
{
	public $user_id = '';
	public $accessToken = '';
	public function __construct($config)
	{
		date_default_timezone_set('America/Los_Angeles');
		$dg = new DG();
		$this->facebook = new Facebook\Facebook($config);
		$this->helper = $this->facebook->getJavaScriptHelper();
		
		$case = $this->helper->getSignedRequest();
 
		$this->user_id = $case ? $case->getUserId() : null;
		if($this->user_id) 
		{
			try 
			{
				$this->accessToken = $this->helper->getAccessToken();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				return $e->getMessage();
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				return $e->getMessage();
			}
		}
		
		if($this->accessToken)	
			$_SESSION['fb_access_token'] = (string) $this->accessToken;
		else
			$_SESSION['fb_access_token'] = '';
    }
	
	function getAllAlbum($limit = 24, $direction = '')
	{	
		if($this->user_id && !empty($_SESSION['fb_access_token']))
		{
			try{
				$response =  $this->facebook->get('/me/albums?limit='.$limit.$direction, $_SESSION['fb_access_token']);
				$all_albums = $response->getDecodedBody();
				$album = array();
				$data = array();
				foreach($all_albums['data'] as $key=>$album)
				{
					if(isset($album['id']))
					{
						if(!isset($album['count']))
							$album['count'] = '';
						
						$data[$key]['album_id'] = $album['id'];
						$data[$key]['title'] = $album['name'];
						$data[$key]['time'] = $album['created_time'];
						$data[$key]['count'] = $album['count'];
						$cover_photo = $this->getCoverPhoto($album['id']);
						$data[$key]['cover_photo'] = $cover_photo['picture']['data']['url'];
					}
				}
				$album['data'] = $data;
				$album['page'] = array();
				if(isset($all_albums['paging']) && count($all_albums['paging']))
					$album['page'] = $all_albums['paging'];
				return $album;
			} 
			catch(Facebook\FacebookSDKException $e) 
			{
				return error_log($e->getMessage());
			} 
		}
	}
	
	//get view Photo. return array(photo_medium + photo_big + photo);
	function getPhoto($photo_id)
	{	
		if($this->user_id && !empty($_SESSION['fb_access_token']))
		{	
			$data = array();
			$response = $this->facebook->get('/'.$photo_id.'?fields=images', $_SESSION['fb_access_token']);
			$photo = $response->getDecodedBody();
			$data['photo_medium'] = $photo['images'][1]['source'];
			$data['photo_big'] = $photo['images'][0]['source'];
			$data['photo'] = $photo['images'][4]['source'];
			return $data;
		}
	}
	
	//get cover_photo.
	function getCoverPhoto($album_id)
	{	
		if($this->user_id && !empty($_SESSION['fb_access_token']))
		{	
			$response = $this->facebook->get('/'.$album_id.'?fields=picture', $_SESSION['fb_access_token']);
			return $response->getDecodedBody();
		}
	}
	
	//get All Photo in album. return array(photo_id + photo_medium + photo_big + photo);
	function getAllPhoto($album_id, $limit = 24, $direction = '')
	{	
		if($this->user_id && !empty($_SESSION['fb_access_token']))
		{	
			$data = array();
			$result = array();
			$response = $this->facebook->get('/'.$album_id.'/photos?limit='.$limit.$direction.'&fields=images', $_SESSION['fb_access_token']);
			$photos = $response->getDecodedBody();
			
			foreach($photos['data'] as $key=>$photo)
			{
				if(!isset($photo['created_time']))
					$photo['created_time'] = '';
				
				$data[$key]['photo_medium'] = $photo['images'][1]['source'];
				$data[$key]['photo_big'] = $photo['images'][0]['source'];
				$data[$key]['photo'] = $photo['images'][4]['source'];
				$data[$key]['time'] = $photo['created_time'];
			}
			$result['data'] = $data;
			$result['page'] = array();
			if(isset($photos['paging']) && count($photos['paging']))
				$result['page'] = $photos['paging'];
			
			return $result;
		}
	}
}	
?>