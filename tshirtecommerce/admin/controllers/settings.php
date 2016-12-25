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

class Settings extends Controllers
{
	public function index()
	{
		// get lang.
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'settings.json';
		$currency = ROOT.DS.'data'.DS.'currencies.json';
		
		$languages = $dg->readFile(dirname(ROOT).DS.'data'.DS.'languages.json');
		$data['languages'] = json_decode($languages, true);
		
		$langplus = dirname(ROOT).DS.'data'.DS.'lang_plus.ini';
			
		if(file_exists($langplus))
			$lang = @parse_ini_file($langplus);
		
		$langdata = @parse_ini_file(dirname(ROOT).DS.'data'.DS.'lang.ini');
		
		if(count($data['languages']))
		{
			foreach($data['languages'] as $val)
			{
				if($val['default'])
				{
					if(file_exists(dirname(ROOT).DS.'data'.DS.$val['file']))
					{
						$langs = @parse_ini_file(dirname(ROOT).DS.'data'.DS.$val['file']);
						if(count($langs))
							$langdata = $langs;
					}
				}
			}
		}
		foreach($langdata as $key=>$val)
		{
			$lang[$key] = $val;
		}
		$data['lang'] = $lang;
		
		if(isset($_POST['setting']))
		{
			$setting = json_encode($_POST['setting']);
			$dg->WriteFile($file, $setting);
		}
		
		$setting = $dg->readFile($file);
		$settings = json_decode($setting, true);
		//get currencies.
		$currencies = $dg->readFile($currency);
		$currencies = json_decode($currencies, true);
		$data['currencies'] = $currencies;
		
		$data['settings'] = $settings;
		
		$data['addons_lang'] = $this->dirToArray(dirname(ROOT).DS.'addons'.DS.'language');
		
		$data['breadcrumb'] = lang('breadcrumb_settings', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		
		$this->view('settings', $data);
	}
	
	public function editLang($admin = 'admin')
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		if(!empty($_POST['language']))
		{
			// Fix post data when translate language.
			$data = json_decode($_POST['language'], true);
			if(count($data) == 0)
			{
				$lang = str_replace('\\', '', $_POST['language']);
				$data = json_decode($lang, true);
			}
			// End fix.
			
			if(is_array($data))
			{
				$res = '';
				foreach($data as $key=>$val)
				{
					$val = str_replace('\\', '\\\\', $val);
					$res .= $key .'= "'. addslashes($val) .'"'.PHP_EOL;
				}
				
				if(!empty($_POST['file']) && file_exists(dirname(ROOT).DS.'data'.DS.$_POST['file']))
				{
					if(!empty($_POST['addon']))
					{
						$addon = dirname(ROOT).DS.'addons'.DS.'language'.DS.str_replace('language_', '', str_replace('.ini', '', $_POST['file']));
						if(!is_dir($addon))
							mkdir($addon, 0755, true);
							
						$url = $addon.DS.$_POST['addon'];
						file_put_contents($url, '');
					}else
					{
						$url = dirname(ROOT).DS.'data'.DS.$_POST['file'];
					}
				}else
				{
					$url = dirname(ROOT).DS.'data'.DS.'lang.ini';
				}
				//update ini file.
				if(file_exists($url))
				{
					$check = $dg->WriteFile($url, $res);
					if($check)
					{
						echo lang('settings_lang_update_sucess_msg');
					}else
					{
						echo lang('settings_lang_update_error_msg');
					}
				}else
				{
					echo lang('settings_lang_update_error_msg');
				}
			}else
			{
				echo lang('settings_lang_update_error_msg');
			}
		}else
		{
			redirect(site_url('index.php/settings'));
		}
	}
	
	public function colors()
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'colors.json';
		$color = $dg->readFile($file);
		$colors = json_decode($color, true);
		if(isset($colors['colors']))
			$data['colors'] = $colors['colors'];
		else
			$data['colors'] = array();
			
		//sort array().
		$sort = array();
		foreach($data['colors'] as $key=>$val)
		{
			$count = 0;
			$vl = array();
			foreach($data['colors'] as $k=>$v)
			{
				if($count <= $k && !isset($sort[$k]))
				{
					$count = $k;
					$vl = $v;
				}
			}
			$sort[$count] = $vl;
		}
		
		$data['colors'] = $sort;
		
		$data['breadcrumb'] = lang('breadcrumb_colors', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		$this->view('setting_colors', $data);
	}
	
	public function pageColor($segment = 0)
	{
		// get fonts.
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'colors.json';
		$color = $dg->readFile($file);
		$colors = json_decode($color, true);
		if(isset($colors['colors']))
		{
		
			$search = array();
			foreach($colors['colors'] as $key=>$val)
			{
				if(!empty($_POST['search_color']))
				{
					if(strpos(strtolower($val['title']), strtolower($_POST["search_color"])) !== false)
						$search[$key] = $val;
				}else
				{
					$search[$key] = $val;
				}
			}
			
			//sort array().
			$sort = array();
			foreach($search as $key=>$val)
			{
				$count = 0;
				$vl = array();
				foreach($search as $k=>$v)
				{
					if($count <= $k && !isset($sort[$k]))
					{
						$count = $k;
						$vl = $v;
					}
				}
				$sort[$count] = $vl;
			}
			
			$pagecolor = array();
			if(isset($_POST['per_page']))
				$perpage = $_POST['per_page'];
			else
				$perpage = 10; 
				
			if($perpage == 'all')
				$perpage = count($sort);
			$j = 1;
			foreach($sort as $key=>$val)
			{
				if($j > $segment && $j <= ($perpage+$segment))
					$pagecolor[$key] = $sort[$key];
				$j++;
			}
			
			if($perpage < count($sort))
				$data['page'] = $perpage;
			else
				$data['page'] = 0;
			$data['colors'] = $pagecolor;
			$data['total'] = count($sort);
			$data['segment'] = $segment;
			include_once(ROOT.DS.'views/setting_color.php');
		}else
		{
			return;
		}
	}
	
	public function editColor($id = '')
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'colors.json';
		
		if(isset($_POST['data']))
		{
			$post = $_POST['data'];
			$colors = array();
			if(file_exists($file))
			{
				$color = $dg->readFile($file);
				$colors = json_decode($color, true);
				$colors = $colors['colors'];
			}
			if($id == '')
			{
				$colors[] = array(
					'title'=>$post['title'],
					'hex'=>$post['hex'],
				);
			}else
			{
				if(isset($colors[$id]))
				{
					$colors[$id] = array(
						'title'=>$post['title'],
						'hex'=>$post['hex'],
					);
				}
			}
			$res = array();
			$res['status'] = 1;
			$res['colors'] = $colors;
			$result = json_encode($res);
			$dg->WriteFile($file, $result);
			
			$this->pageColor(0);
			return;
		}
		
		if($id != '')
		{
			$color = $dg->readFile($file);
			$colors = json_decode($color, true);
			$colors = $colors['colors'];
			$color = $colors[$id];
		}
		$data['breadcrumb'] = lang('breadcrumb_edit_color', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		include_once(ROOT.DS.'views/edit_color.php');
	}
	
	public function removeColor()
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'colors.json';
		$color = $dg->readFile($file);
		$colors = json_decode($color, true);
		
		if(isset($_POST['checkb']))
		{
			foreach($_POST['checkb'] as $id)
			{	
				if(isset($colors['colors'][$id]))
				{
					unset($colors['colors'][$id]);
				}
			}
			$data_colors = $colors['colors'];
			$colors['colors'] = array();
			foreach($data_colors as $val)
			{
				$colors['colors'][] = $val;
			}
			$res = json_encode($colors);
			$dg->WriteFile($file, $res);
		}
		$this->pageColor(0);
		return;
	}
	
	public function fonts()
	{
		// get fonts.
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'fonts.json';
		$font = $dg->readFile($file);
		$fonts = json_decode($font, true);
		if(isset($fonts['fonts']['fonts']))
			$data['fonts'] = $fonts['fonts']['fonts'];
		else
			$data['fonts'] = array();
		
		//sort array().
		$sort = array();
		foreach($data['fonts'] as $key=>$val)
		{
			$count = 0;
			$vl = array();
			foreach($data['fonts'] as $k=>$v)
			{
				if($count <= $k && !isset($sort[$k]))
				{
					$count = $k;
					$vl = $v;
				}
			}
			$sort[$count] = $vl;
		}
		
		$data['fonts'] = $sort;
			
		// get cate.
		$file = ROOT.DS.'data'.DS.'font_categories.json';
		$cates = $dg->readFile($file);
		$data['cates'] = json_decode($cates, true);
		
		$data['breadcrumb'] = lang('breadcrumb_fonts', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		$this->view('fonts', $data);
	}
	
	public function pageFont($segment = 0)
	{
		// get fonts.
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'fonts.json';
		$font = $dg->readFile($file);
		$fonts = json_decode($font, true);
		if(isset($fonts['fonts']['fonts']))
		{
		
			$search = array();
			foreach($fonts['fonts']['fonts'] as $key=>$val)
			{
				if(!empty($_POST['search_font']) && isset($_POST['option_font']) && $_POST['option_font'] !== '')
				{
					if((strpos(strtolower($val['title']), strtolower($_POST["search_font"])) !== false) && $_POST['option_font'] == $val['cate_id'])
					{
						$search[$key] = $val;
					}
				}elseif(!empty($_POST['search_font']))
				{
					if(strpos(strtolower($val['title']), strtolower($_POST["search_font"])) !== false)
						$search[$key] = $val;
				}elseif(isset($_POST['option_font']) && $_POST['option_font'] !== '')
				{
					if($_POST['option_font'] == $val['cate_id'])
					{
						$search[$key] = $val;
					}
				}else
				{
					$search[$key] = $val;
				}
			}
			
			//sort array().
			$sort = array();
			foreach($search as $key=>$val)
			{
				$count = 0;
				$vl = array();
				foreach($search as $k=>$v)
				{
					if($count <= $k && !isset($sort[$k]))
					{
						$count = $k;
						$vl = $v;
					}
				}
				$sort[$count] = $vl;
			}
			//echo '<pre>'; print_r($sort);exit;
			$pagefont = array();
			if(isset($_POST['per_page']))
				$perpage = $_POST['per_page'];
			else
				$perpage = 10; 
				
			if($perpage == 'all')
				$perpage = count($sort);
			$j = 1;
			foreach($sort as $key=>$val)
			{
				if($j > $segment && $j <= ($perpage+$segment))
					$pagefont[$key] = $sort[$key];
				$j++;
			}
			
			if($perpage < count($sort))
				$data['page'] = $perpage;
			else
				$data['page'] = 0;
			$data['fonts'] = $pagefont;
			$data['total'] = count($sort);
			$data['segment'] = $segment;
			include_once(ROOT.DS.'views/font.php');
		}else
		{
			return;
		}
	}
	
	public function editFont($id = '')
	{	
		$file = dirname(ROOT).DS.'data'.DS.'fonts.json';
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		include_once(ROOT.DS.'includes'.DS.'upload.php');
		$dg = new dg();
		
		$fonts = $dg->readFile($file);
		$fonts = json_decode($fonts, true);
		$font = $fonts['fonts']['fonts'];
		
		$data = array();
		
		$data['error'] = '';
		$data['id'] = $id;
		
		// get categories.
		$cate_font = ROOT.DS.'data'.DS.'font_categories.json';
		$cate = $dg->readFile($cate_font);
		$cates = json_decode($cate, true);
		$data['categories'] = $cates;
		
		// post data.
		if(isset($_POST['title']) && $_POST['title'] != '')
		{
			$title = $_POST['title'];
			//upload.
			$path = dirname(ROOT).DS.'data'.DS.'fonts';
			if(!file_exists($path))
				mkdir($path, 0755);
				
			$up = new upload();
			$up->permission = 755;
			$up->path = $path;
			$up->file_size = 2097152; // 2mb.
			$count = 0;
			$result = array();
			if(count($_FILES) == 3)
			{
				foreach($_FILES as $key => $value)
				{
					if(isset($_FILES[$key]['name']) && $_FILES[$key]['name'] != '')
					{
						$checkname = array('~', '`', '!', '@', '#', '$', '%', '^', '&', '(', ')', '+', '=', '[',']', '{','}', ':', ' ', ',', '\'', ';');
						$up->file_name = str_replace( $checkname , '', $_FILES[$key]['name'] );
						
						if($count == 0)
						{
							$up->file_type[0] = 'woff';
							$val = $up->file($_FILES[$key]);
							if($val['error'] == 1)
							{
								$data['error'] = $val['msg'];
								break;
							}
							$result[$count] = $val;
						}elseif($count == 1)
						{
							$up->file_type[0] = 'ttf';
							$up->file_type[1] = 'TTF';
							$val = $up->file($_FILES[$key]);
							if($val['error'] == 1)
							{
								$data['error'] = $val['msg'];
								if(file_exists($result[0]['full_path']))
									unlink($result[0]['full_path']);
								break;
							}
							$result[$count] = $val;
						}else
						{
							$up->file_type[0] = 'jpg';
							$up->file_type[1] = 'png';
							$up->file_type[2] = 'gif';
							$up->file_type[3] = 'jpeg';
							$val = $up->file($_FILES[$key]);
							if($val['error'] == 1)
							{
								$data['error'] = $val['msg'];
								if(file_exists($result[0]['full_path']))
									unlink($result[0]['full_path']);
								if(file_exists($result[1]['full_path']))
									unlink($result[1]['full_path']);
								break;
							}
							$result[$count] = $val;
						}
					}
					$count++;
				}
			}
		
			//process.
			$subtitle = '';
			$cate_id  = '';
			$catename = '';
			if(isset($_POST['subtitle']))
				$subtitle = $_POST['subtitle'];
			if(isset($_POST['cate_id']))
				$cate_id = $_POST['cate_id'];
			if($catename == '' && isset($cates[$cate_id]))
				$catename = $cates[$cate_id];
				
			if($id != '')
			{
				if(count($font) && $data['error'] == '')
				{
					$font_out = array();
					foreach($font as $key=>$val)
					{
						if($val['id'] == $id && $val['type'] == '')
						{
							$filename = $val['filename'];
							$path = $val['path'];
							$thumb = $val['thumb'];
							
							if($data['error'] == '' && count($result) == 3)
							{
								$filename = array(
									'woff'=>$result[0]['file_name'],
									'ttf'=>$result[1]['file_name']
								);
								$filename = json_encode($filename);
								$path = $path;
								$thumb = $result[2]['file_name'];
							}
							$font_out[$key] = array(
								'id'=>$val['id'],
								'title'=>$_POST['title'],
								'subtitle'=>$subtitle,
								'filename'=>$filename,
								'path'=>'data/fonts',
								'thumb'=>$thumb,
								'shop_id'=>'',
								'cate_id'=>$cate_id,
								'published'=>'1',
								'catename'=>$catename,
								'type'=>'',
							);
						}else
						{
							$font_out[$key] = $val;
						}
					}
					
					// out data.
					$out['status'] = '1';
					$out['fonts']['google_fonts'] = $fonts['fonts']['google_fonts'];
					$out['fonts']['fonts'] = $font_out;
					
					// get cates.
					foreach($cates as $k=>$v)
					{
						$out['fonts']['cateFonts'][$k]['fonts'] = $this->updateFonts($out['fonts']['fonts'], $k, $v, 'catefont');
						$out['fonts']['categories'][$k] = $this->updateCate($k, $v);
					}
			
					$res = json_encode($out);
				}
			}else
			{
				if($data['error'] == '' && count($result) == 3)
				{
					// get last id.
					if(count($font))
					{
						$font_end = end($fonts['fonts']['fonts']);
						$font_id = $font_end['id']+1;
					}else
					{
						$font_id = 0;
					}
					
					//add a font.
					$filename = array(
						'woff'=>$result[0]['file_name'],
						'ttf'=>$result[1]['file_name']
					);
					
					$font[] = array(
						'id'=>(string)$font_id,
						'title'=>$_POST['title'],
						'subtitle'=>$subtitle,
						'filename'=>json_encode($filename),
						'path'=>'data/fonts',
						'thumb'=>$result[2]['file_name'],
						'shop_id'=>'',
						'cate_id'=>$cate_id,
						'published'=>'1',
						'catename'=>$catename,
						'type'=>'',
					);
					
					// out data.
					$out['status'] = '1';
					$out['fonts']['google_fonts'] = $fonts['fonts']['google_fonts'];
					$out['fonts']['fonts'] = $font;
					
					// get cates.
					foreach($cates as $key=>$val)
					{
						$out['fonts']['cateFonts'][$key]['fonts'] = $this->updateFonts($out['fonts']['fonts'], $key, $val, 'catefont');
						$out['fonts']['categories'][$key] = $this->updateCate($key, $val);
					}
					
					$res = json_encode($out);
				}elseif($data['error'] == '')
				{
					$data['error'] = lang('fonts_add_font_file_error_msg', true);
				}
			}
			if(isset($res) && $res != '')
			{
				$dg->WriteFile($file, $res);
				if($id == '')
					$data['msg'] = lang('fonts_add_font_file_success_msg', true);
				else
					$data['msg'] = lang('fonts_edit_font_file_success_msg', true);
			}
		}
		
		if(empty($subtitle))
			$subtitle = '';
		if(empty($title))
			$title = '';
		$data['font'] = array(
			'title'=>$title,
			'subtitle'=>$subtitle,
			'filename'=>'',
			'path'=>'',
			'cate_id'=>'',
			'thumb'=>'',
		);
			
		if($id == '')
		{
			$data['title'] = lang('breadcrumb_add_font', true);
		}else
		{
			$fonts = $dg->readFile($file);
			$fonts = json_decode($fonts, true);
			$font = $fonts['fonts']['fonts'];
			
			foreach($font as $key=>$val)
			{
				if($val['id'] == $id && $val['type'] == '')
				{
					$data['font'] = array(
						'title'=>$val['title'],
						'subtitle'=>$val['subtitle'],
						'filename'=>$val['filename'],
						'path'=>$val['path'],
						'cate_id'=>$val['cate_id'],
						'thumb'=>$val['thumb'],
					);
				}
			}
			$data['title'] = lang('breadcrumb_edit_font', true);
		}
		$data['sub_title'] = lang('breadcrumb_manager', true);
		$this->view('edit_font', $data);
	}
	
	public function addGoogleFont()
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'fonts.json';
		$font = $dg->readFile($file);
		$fonts = json_decode($font, true);
		if(isset($fonts['fonts']['fonts']))
		{
			$font_name = array();
			foreach($fonts['fonts']['fonts'] as $val)
			{
				$font_name[] = $val['title'];
			}
			$data['fonts'] = json_encode($font_name);
		}else
		{
			$data['fonts'] = '';
		}
		
		// get categories.
		$cate_font = ROOT.DS.'data'.DS.'font_categories.json';
		$cate = $dg->readFile($cate_font);
		$cates = json_decode($cate, true);
		$data['categories'] = $cates;
		
		if(isset($_POST['fonts']))
		{
			// get Post.
			$cate_id = 0;
			$catename = '';
			if(isset($_POST['cate_id']))
				$cate_id = $_POST['cate_id'];
			if(isset($_POST['catename']))
				$catename = $_POST['catename'];
				
			if($catename == '' && !empty($cates[$cate_id]))
				$catename = $cates[$cate_id];
				
			$posts = $_POST['fonts'];
			
			//get font.
			if(isset($fonts['fonts']['fonts']))
				$font = $fonts['fonts']['fonts'];
			else
				$font = array();
			
			$google_fonts = '';
			if(count($font))
			{
				$google_fonts = $fonts['fonts']['google_fonts'];
				$font_end = end($fonts['fonts']['fonts']);
				$font_id = $font_end['id']+1;
			}else
			{
				$font_id = 0;
			}
			// get fonts.
			foreach($posts as $val)
			{
				$name = str_replace(' ', '+', $val);
				if($google_fonts == '')
					$google_fonts .= $name;
				else
					$google_fonts .= '|'.$name;
					
				$font[] = array(
					'id'=>(string)$font_id,
					'title'=>$val,
					'subtitle'=>$val,
					'filename'=>'',
					'path'=>'',
					'thumb'=>'',
					'shop_id'=>'',
					'cate_id'=>$cate_id,
					'published'=>'1',
					'catename'=>$catename,
					'type'=>'google',
				);
				$font_id++;
			}
					
			$result = array();
			$result['status'] = '1';
			$result['fonts']['google_fonts'] = $google_fonts;
			$result['fonts']['fonts'] = $font;
			
			// get cates.
			foreach($cates as $key=>$val)
			{
				$result['fonts']['cateFonts'][$key]['fonts'] = $this->updateFonts($result['fonts']['fonts'], $key, $val, 'catefont');
				$result['fonts']['categories'][$key] = $this->updateCate($key, $val);
			}
			
			$dg->WriteFile($file, json_encode($result));
			return;
		}
		
		$google					= $dg->readFile(dirname(ROOT).DS.'data'.DS.'googlefonts.json');
		$google					= json_decode($google, true);
		$data['google']			= $google;
			
		$data['title'] = lang('breadcrumb_add_font_google', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		$this->view('googlefont', $data);
	}
	
	public function cateFont()
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = ROOT.DS.'data'.DS.'font_categories.json';
		$cate = $dg->readFile($file);
		$cates = json_decode($cate, true);
			
		if(isset($_POST['catename']))
		{
			$cate_id = 1;
			if(count($cates))
			{
				foreach($cates as $key=>$val)
				{
					if($cate_id < $key)
						$cate_id = $key;
				}
				$cate_id = $cate_id+1;
			}
			
			$filefont = dirname(ROOT).DS.'data'.DS.'fonts.json';
			$fonts = $dg->readFile($filefont);
			$fonts = json_decode($fonts, true);
			
			if(isset($_POST['id']))
			{
				$id = $_POST['id'];
				$cates[$id] = $_POST['catename'];
				$result = json_encode($cates);
				if($dg->WriteFile($file, $result))
				{
					$fonts['fonts']['fonts'] = $this->updateFonts($fonts['fonts']['fonts'], $id, $_POST['catename']);
				
					foreach($cates as $key=>$val)
					{
						if($key == $id)
							$val = $_POST['catename'];
						$fonts['fonts']['cateFonts'][$key]['fonts'] = $this->updateFonts($fonts['fonts']['fonts'], $key, $val, 'catefont');
						$fonts['fonts']['categories'][$key] = $this->updateCate($key, $val);
					}
					$dg->WriteFile($filefont, json_encode($fonts));
				}
			}else
			{
				$cates[$cate_id] = $_POST['catename'];
				$result = json_encode($cates);
				if($dg->WriteFile($file, $result))
				{
					$fonts['fonts']['cateFonts'][$cate_id]['fonts'] = $this->updateFonts($fonts['fonts']['fonts'], $cate_id, $_POST['catename'], 'catefont');
					$fonts['fonts']['categories'][] = $this->updateCate($cate_id, $_POST['catename']);
					$dg->WriteFile($filefont, json_encode($fonts));
				}
			}
			
			// get data.
			$cate = $dg->readFile($file);
			$cates = json_decode($cate);
			foreach($cates as $key=>$cate)
			{
				echo '<option value="'.$key.'">'.$cate.'</option>';
			}
			return;
		}else
		{
			return;
		}
	}
	
	public function delCateFont()
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = ROOT.DS.'data'.DS.'font_categories.json';
		$cate = $dg->readFile($file);
		$cates = json_decode($cate, true);
			
		if(isset($_POST['id']))
		{
			$filefont = dirname(ROOT).DS.'data'.DS.'fonts.json';
			$fonts = $dg->readFile($filefont);
			$fonts = json_decode($fonts, true);
			
			$id = $_POST['id'];
			
			if(isset($cates[$id]))
				unset($cates[$id]);
				
			$result = json_encode($cates);
			// write and update fonts.
			if($dg->WriteFile($file, $result))
			{
				$fonts['fonts']['fonts'] = $this->updateFonts($fonts['fonts']['fonts'], $id, 'Root', 'remove', 0);
				
				if(isset($fonts['fonts']['cateFonts'][$id]))
					unset($fonts['fonts']['cateFonts'][$id]);
				if(isset($fonts['fonts']['categories'][$id]))
					unset($fonts['fonts']['categories'][$id]);
				$dg->WriteFile($filefont, json_encode($fonts));
			}
			
			// get data.
			$cate = $dg->readFile($file);
			$cates = json_decode($cate);
			foreach($cates as $key=>$cate)
			{
				echo '<option value="'.$key.'">'.$cate.'</option>';
			}
			return;
		}else
		{
			return;
		}
	}
	
	public function publish($id = '')
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'fonts.json';
		$font = $dg->readFile($file);
		$fonts = json_decode($font, true);
		if(isset($_POST['checkb']))
		foreach($_POST['checkb'] as $id)
		{
			if(isset($fonts['fonts']['fonts'][$id]))
			{
				$fonts['fonts']['fonts'][$id] = array(
						'id'=>$fonts['fonts']['fonts'][$id]['id'],
						'title'=>$fonts['fonts']['fonts'][$id]['title'],
						'subtitle'=>$fonts['fonts']['fonts'][$id]['subtitle'],
						'filename'=>$fonts['fonts']['fonts'][$id]['filename'],
						'path'=>$fonts['fonts']['fonts'][$id]['path'],
						'thumb'=>$fonts['fonts']['fonts'][$id]['thumb'],
						'shop_id'=>'',
						'cate_id'=>$fonts['fonts']['fonts'][$id]['cate_id'],
						'published'=>'1',
						'catename'=>$fonts['fonts']['fonts'][$id]['catename'],
						'type'=>$fonts['fonts']['fonts'][$id]['type'],
					);
			}
		}
		
		$res = json_encode($fonts);
		$dg->WriteFile($file, $res);
		
		$this->pagefont();
		return;
	}
	
	public function unpublish()
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'fonts.json';
		$font = $dg->readFile($file);
		$fonts = json_decode($font, true);
		if(isset($_POST['checkb']))
		foreach($_POST['checkb'] as $id)
		{
			if(isset($fonts['fonts']['fonts'][$id]))
			{
				$fonts['fonts']['fonts'][$id] = array(
						'id'=>$fonts['fonts']['fonts'][$id]['id'],
						'title'=>$fonts['fonts']['fonts'][$id]['title'],
						'subtitle'=>$fonts['fonts']['fonts'][$id]['subtitle'],
						'filename'=>$fonts['fonts']['fonts'][$id]['filename'],
						'path'=>$fonts['fonts']['fonts'][$id]['path'],
						'thumb'=>$fonts['fonts']['fonts'][$id]['thumb'],
						'shop_id'=>'',
						'cate_id'=>$fonts['fonts']['fonts'][$id]['cate_id'],
						'published'=>'0',
						'catename'=>$fonts['fonts']['fonts'][$id]['catename'],
						'type'=>$fonts['fonts']['fonts'][$id]['type'],
					);
			}
		}
		
		$res = json_encode($fonts);
		$dg->WriteFile($file, $res);
		
		$this->pagefont();
		return;
	}
	
	public function removeFont()
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'fonts.json';
		$font = $dg->readFile($file);
		$fonts = json_decode($font, true);
		if(isset($_POST['checkb']))
		foreach($_POST['checkb'] as $id)
		{
			if(isset($fonts['fonts']['fonts'][$id]))
			{
				// unset catefonts.
				$cate_id = $fonts['fonts']['fonts'][$id]['cate_id'];
				if(isset($fonts['fonts']['cateFonts'][$cate_id]['fonts']) && count($fonts['fonts']['cateFonts'][$cate_id]['fonts']))
				{
					foreach($fonts['fonts']['cateFonts'][$cate_id]['fonts'] as $k=>$v)
					{
						if($v['id'] == $id)
							unset($fonts['fonts']['cateFonts'][$cate_id]['fonts'][$k]);
					}
				}
				
				$fontremove = str_replace(' ', '+', $fonts['fonts']['fonts'][$id]['title']);
				$fonts['fonts']['google_fonts'] = str_replace($fontremove.'|', '', $fonts['fonts']['google_fonts']);
				$fonts['fonts']['google_fonts'] = str_replace($fontremove, '', $fonts['fonts']['google_fonts']);
				if($fonts['fonts']['fonts'][$id]['type'] != 'google')
				{
					$path = dirname(ROOT).DS.$fonts['fonts']['fonts'][$id]['path'];
					if(file_exists($path.DS.$fonts['fonts']['fonts'][$id]['thumb']))
						unlink($path.DS.$fonts['fonts']['fonts'][$id]['thumb']);
					$filename = json_decode($fonts['fonts']['fonts'][$id]['filename'], true);
					if(isset($filename['woff']) && file_exists($path.DS.$filename['woff']))
						unlink($path.DS.$filename['woff']);
					if(isset($filename['ttf']) && file_exists($path.DS.$filename['ttf']))
						unlink($path.DS.$filename['ttf']);
				}
				unset($fonts['fonts']['fonts'][$id]);
			}
		}
		$datafont = $fonts['fonts']['fonts'];
		$fonts['fonts']['fonts'] = array();
		foreach($datafont as $val)
		{
			$fonts['fonts']['fonts'][] = $val;
		}
		
		$res = json_encode($fonts);
		$dg->WriteFile($file, $res);
		
		$this->pagefont();
		return;
	}
	
	private function updateFonts($fonts = array(), $cate_id, $catename = 'Root', $type = 'update', $cate_default = 0)
	{	
		$results = array();
		foreach($fonts as $key=>$val)
		{
			if($type == 'remove')
			{
				$results[$key] = $val;
				if($val['cate_id'] == $cate_id)
				{
					$results[$key]['cate_id'] = $cate_default;
					$results[$key]['catename'] = $catename;
				}
			}else if($type == 'update')
			{
				$results[$key] = $val;
				if($val['cate_id'] == $cate_id)
					$results[$key]['catename'] = $catename;
			}else if($type == 'catefont')
			{
				if($val['cate_id'] == $cate_id)
				{
					$results[$key] = $val;
					$results[$key]['catename'] = $catename;
				}
			}
		}
		return $results;
	}
	
	private function updateCate($id, $catename)
	{
		return array(
			'cate_id'=>$id,
			'title'=>$catename,
			'slug'=>'',
			'description'=>'',
			'id'=>$id,
			"type"=>"font",
			"parent_id"=>0,
			"level"=>1,
			"created"=>'',
			"create_by"=>'',
			"published"=>1,
			"params"=>"",
			"order"=>0,
			"layout"=>0,
			"image"=>"",
			"language"=>"en",
			"meta_title"=>"",
			"meta_keyword"=>"",
			"meta_description"=>""
		);
	}
	
	public function Languages($tyle = '')
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'languages.json';
		if(file_exists($file))
			$languages = $dg->readFile($file);
		else
			$languages = '[]';
			
		$languages = json_decode($languages, true);
			
		//sort array().
		$sort = array();
		if(count($languages))
		{
			foreach($languages as $key=>$val)
			{
				$count = 0;
				$vl = array();
				foreach($languages as $k=>$v)
				{
					if($count <= $k && !isset($sort[$k]))
					{
						$count = $k;
						$vl = $v;
					}
				}
				$sort[$count] = $vl;
			}
		}
		
		$data['languages'] = $sort;
		
		if($tyle == '')
		{	
			$data['breadcrumb'] = lang('breadcrumb_languages', true);
			$data['sub_title'] = lang('breadcrumb_manager', true);
			$this->view('languages', $data);
		}else
		{
			$this->view('language', $data);
		}
	}
	
	//Publish and UnPublish language
	public function publishLanguage($code = '', $value = '')
	{		
		if ($code != '' && $value != '')
		{
			include_once(ROOT.DS.'includes'.DS.'functions.php');
			$dg = new dg();
			
			$file = dirname(ROOT).DS.'data'.DS.'languages.json';
			if(file_exists($file))
				$languages = $dg->readFile($file);
			else
				$languages = '[]';
				
			$languages = json_decode($languages, true);
			
			$data = array();
			if(count($languages))
			{
				foreach($languages as $k=>$language)
				{
					if (!isset($language['published']))
						$language['published'] = 1;
					
					if ($language['code'] == $code)
					{
						$language['published'] = $value;
					}
					$data[$k] = $language;
				}
				$dg->WriteFile($file, json_encode($data));
			}
		}
		$dg->redirect('index.php/settings/languages');
		exit;
	}
	
	public function getlang()
	{
		if(isset($_POST['addon']) && $_POST['addon'] != '')
			$langplus = dirname(ROOT).DS.'addons'.DS.'language'.DS.str_replace('.ini', '_plus.ini', $_POST['addon']);
		else
			$langplus = dirname(ROOT).DS.'data'.DS.'lang_plus.ini';	
		
		if(file_exists($langplus))
			$lang = @parse_ini_file($langplus);
		
		if(isset($_POST['addon']) && $_POST['addon'] != '')
			$langdata = @parse_ini_file(dirname(ROOT).DS.'addons'.DS.'language'.DS.$_POST['addon']);
		else
			$langdata = @parse_ini_file(dirname(ROOT).DS.'data'.DS.'lang.ini');
		
		
		if(isset($_POST['file']) && $_POST['file'] != '')
		{
			if(isset($_POST['addon']) && $_POST['addon'] != '')
				$file = dirname(ROOT).DS.'addons'.DS.'language'.DS.str_replace('language_', '', str_replace('.ini', '', $_POST['file'])).DS.$_POST['addon'];
			else
				$file = dirname(ROOT).DS.'data'.DS.$_POST['file'];
						
			if(file_exists($file))
			{
				$langs = @parse_ini_file($file);
				if(count($langs))
					$langdata = $langs;
			}
		}
		
		foreach($langdata as $key=>$val)
		{
			$lang[$key] = $val;
		}
		
		if(count($lang))
		{
			foreach($lang as $key=>$val)
			{
				echo '<li><p class="click_edit" data-label="'.$key.'">'.stripslashes($val).'</p></li>';
			}
		}
		exit;
	}
	
	public function editLanguage($id = '')
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'languages.json';
		
		if(isset($_POST['data']))
		{
			$post = $_POST['data'];
			$post['file'] = 'language_'.$post['code'].'.ini';
			$languages = array();
			if(file_exists($file))
			{
				$languages = $dg->readFile($file);
				$languages = json_decode($languages, true);
			}
			
			//check lang code.
			if(count($languages))
			{
				foreach($languages as $key=>$lang)
				{
					if($post['code'] == $lang['code'] && $key != $id)
					{
						$this->languages('load');
						return;
					}
				}
			}
			
			if($id === '')
			{
				$post['default'] = 0;
				$languages[] = $post;
				file_put_contents(dirname(ROOT).DS.'data'.DS.$post['file'], '');
			}else
			{
				if(isset($languages[$id]))
				{
					$post['default'] = $languages[$id]['default'];
					if(file_exists(dirname(ROOT).DS.'data'.DS.$languages[$id]['file']))
						rename(dirname(ROOT).DS.'data'.DS.$languages[$id]['file'], dirname(ROOT).DS.'data'.DS.$post['file']);
						
					if(is_dir(dirname(ROOT).DS.'data'.DS.$languages[$id]['file']))
						rename(dirname(ROOT).DS.'addons'.DS.'language'.DS.$languages[$id]['code'], dirname(ROOT).DS.'addons'.DS.'language'.DS.$post['code']);
						
					$languages[$id] = $post;
				}
			}
			
			$result = json_encode($languages);
			$dg->WriteFile($file, $result);
			
			$this->languages('load');
			return;
		}
		
		if($id !== '')
		{
			$languages = $dg->readFile($file);
			$languages = json_decode($languages, true);
			$language = $languages[$id];
		}
		$data['breadcrumb'] = lang('breadcrumb_edit_language', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		include_once(ROOT.DS.'views/edit_language.php');
	}
	
	function languageDefault($id = '')
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'languages.json';
		if(file_exists($file))
			$languages = $dg->readFile($file);
		else
			$languages = '[]';
			
		$languages = json_decode($languages, true);
		
		$result = array();
		foreach($languages as $key=>$val)
		{
			$result[$key] = $val;
			if($id == $key)
				$result[$key]['default'] = 1;
			else
				$result[$key]['default'] = 0;
		}
		
		$result = json_encode($result);
		if($dg->WriteFile($file, $result))
		
		$this->languages('load');
		return;
	}
	
	public function removeLanguage()
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		
		$file = dirname(ROOT).DS.'data'.DS.'languages.json';
		$languages = $dg->readFile($file);
		$languages = json_decode($languages, true);
		
		if(isset($_POST['checkb']))
		{
			foreach($_POST['checkb'] as $id)
			{	
				if(isset($languages[$id]))
				{
					if(isset($languages[$id]['code']) && file_exists(dirname(ROOT).DS.'data'.DS.$languages[$id]['file']))
					{
						unlink(dirname(ROOT).DS.'data'.DS.$languages[$id]['file']);
						if(is_dir(dirname(ROOT).DS.'addons'.DS.'language'.DS.$languages[$id]['code']))
							unlink(dirname(ROOT).DS.'addons'.DS.'language'.DS.$languages[$id]['code']);
					}
					unset($languages[$id]);
				}
			}
			$data_languages = $languages;
			$languages = array();
			foreach($data_languages as $val)
			{
				$languages[] = $val;
			}
			$res = json_encode($languages);
			$dg->WriteFile($file, $res);
		}
		$this->languages('load');
		return;
	}
	
	function dirToArray($dir) 
	{
		$res = array();
		if (is_dir($dir))
		{
			if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false)
				{
					if($file != '.' && $file != '..' && !is_dir($dir.DS.$file) && strpos($file, '_plus.ini') == 0)
						$res[] = $file;
				}
				closedir($dh);
			}
		}
		return $res;
	}
}

?>