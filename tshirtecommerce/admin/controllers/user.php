<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('ROOT')) exit('No direct script access allowed');

class User extends Controllers
{
	public $config;
	
	public function index($error = 0)
	{
		$data 	= array();		
		
		if($this->session('login') !== false)
		{
			$dgClass->redirect('index.php/dashboard');
		}
		else
		{
			$version = 'wordpress';
			$file = dirname(ROOT) .DS. 'version.json';
	
			if (file_exists($file))
			{
				$content = file_get_contents($file);
				if ($content != false)
				{
					$versions = json_decode($content);
					if (isset($versions->platforms))
					{
						$version = $versions->platforms;
					}
				}
				
			}
			
			$url 	= site_url();
			$temp 	= explode('tshirtecommerce', $url);
			
			if ($version == 'wordpress')
				$url 	= $temp[0].'wp-admin';
			else
				$url 	= $temp[0].'admin';
			
			$data['url'] 	= $url;
			
			$data['version'] 	= $version;
			$data['title'] 		= lang('user_login_sign_in', true);
			
			$this->modal('login', $data);				
		}			
	}	
}
?>