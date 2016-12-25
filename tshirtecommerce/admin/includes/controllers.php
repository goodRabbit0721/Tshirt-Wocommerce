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

class Controllers
{
	public $params = array();
	public $control;
	public $fun;
	
	public function __construct()
	{
		$uri = $_SERVER["REQUEST_URI"];
		
		$str = explode('index.php?/', $uri);
		if (count($str) > 1)
		{
			$params = explode('/', $str[1]);
			if (empty($params[1]))
				$params[1] = 'index';
		}
		else
		{
			$params = array('dashboard', 'index');
		}		
		$this->params	= $params;
	}
	
	// load control
	public function load()
	{
		if ( isset($_GET['session_id']) )
		{
			$session_id = $_GET['session_id'];
			session_id($session_id);			
		}
		else if( isset($_COOKIE['design_session_id']) )
		{
			session_id($_COOKIE['design_session_id']);			
		}

		// fixed not have session.
		$check = false;
		if(!isset($_COOKIE['loginTshirtVariable']) && isset($_GET['session_id']))
		{
			$check = true;
			echo '<script>
				if(typeof window.parent.loginTshirtVariable != "undefined")
					document.cookie="loginTshirtVariable="+JSON.stringify(window.parent.loginTshirtVariable);
			</script>';
		}
		
		session_start();
			
		$params = $this->params;
		
		if(isset($_COOKIE['loginTshirtVariable']) && !isset($_SESSION['is_admin']))
		{
			$_SESSION['is_admin'] = json_decode($_COOKIE['loginTshirtVariable'], true);
		}
		
		if (isset($_SESSION['is_admin']))
		{
			
			$user = $_SESSION['is_admin'];			
			if (isset($user['login']) && $user['login'] == 1)
			{
				$this->set_session('login', true);
				$this->set_session('admin', $user);
			}
		}
		else
		{
			$this->set_session('login', false);
		}
		
		// check login
		if($this->session('login') == false)
		{
			if($params[0] != 'user')
				$params[1] = 'index';
			
			$params[0]	= 'user';
			if (empty($params[1]))
				$params[1] = 'index';
		}		
		

		$control = $params[0];
		
		$file = ROOT .DS. 'controllers' .DS. $control. '.php';
		
		// check control
		if ( !file_exists($file) )
		{
			$this->error();
		}
		
		// load file
		include_once($file);
		
		// check clas
		if (!class_exists($control))
		{
			$this->error();			
		}
		
		$dg = new dg();		
		$lang = $dg->lang();
		$site_url = $dg->url();
		$GLOBALS['lang'] = $lang;
		$GLOBALS['site_url'] = $site_url . 'tshirtecommerce/admin/';

		// redirect.
		if($check && !isset($_SESSION['is_admin']))
			echo '<script>window.location = "'.$site_url . 'tshirtecommerce/admin";</script>';
		
		$controlClass = new $control();
		
		// check function
		if (empty($params[1]))
		{
			$fun = 'index';
		}
		else
		{
			$fun = $params[1];
		}
		
		if( !method_exists($controlClass, $fun) )
		{
			$this->error();	
		}
		
		$this->control = $control;
		$this->fun = $fun;
		
		if (isset($params[2]))
		{
			$array = array();
			for($i=2; $i < count($params); $i++)
			{
				$array[] = $params[$i];
			}
			call_user_func_array(array($controlClass, $fun), $array);
		}
		else
		{
			$controlClass->$fun();
		}
	}
	
	public function view($view, $data)
	{
		// call language
		$segments = $this->params;
		
		// call language
		$dg = new dg();		
		$lang = $dg->lang();
		$site_url = $dg->url();
		$GLOBALS['lang'] = $lang;
		$GLOBALS['site_url'] = $site_url . '/tshirtecommerce/admin/';
		
		ob_start();
		// call add-ons
		include (ROOT .DS. 'includes' .DS. 'addons.php');
		$addons = new addons();	
		
		if (file_exists(ROOT .DS. 'theme' .DS. $view .'.php'))
			require_once(ROOT .DS. 'theme' .DS. $view .'.php');
		elseif(file_exists(ROOT .DS. 'views' .DS. $view .'.php'))
			require_once(ROOT .DS. 'views' .DS. $view .'.php');
		else
			echo 'View not found';
		$content = ob_get_contents();
		ob_end_clean();
		
		if (empty($data['title']))
			$title = 'T-Shirt eCommerce';
		else
			$title = $data['title'];
		
		if (empty($data['sub_title']))
			$sub_title = '';
		else
			$sub_title = $data['sub_title'];
		
		require_once(ROOT .DS. 'layout.php');
	}
	
	public function modal($view, $data)
	{
		// call language
		$segments = $this->params;
		
		// call language
		$dg = new dg();		
		$lang = $dg->lang();
		$site_url = $dg->url();
		$GLOBALS['lang'] = $lang;
		$GLOBALS['site_url'] = $site_url . '/tshirtecommerce/admin/';
		
		// call add-ons
		include (ROOT .DS. 'includes' .DS. 'addons.php');
		$addons = new addons();	
		
		if (file_exists(ROOT .DS. 'theme' .DS. $view .'.php'))
			require_once(ROOT .DS. 'theme' .DS. $view .'.php');		
		elseif(file_exists(ROOT .DS. 'views' .DS. $view .'.php'))
			require_once(ROOT .DS. 'views' .DS. $view .'.php');	
		else
			echo 'View not found';
	}
	
	// load 404
	private function error()
	{
		include_once (ROOT .DS. '404.php');
		exit();
	}
	
	public function set_session($name, $val)
	{
		$_SESSION[$name] = $val;
	}
	
	public function session($name)
	{
		if(isset($_SESSION[$name]))
		{
			return $_SESSION[$name];
		}
		else
		{
			return false;
		}
	}
	
	public function unset_session($name)
	{
		if(isset($_SESSION[$name]))
			unset($_SESSION[$name]);
	}
}
