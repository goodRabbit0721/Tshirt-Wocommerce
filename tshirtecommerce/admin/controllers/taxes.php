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

class Taxes extends Controllers
{
	public function index()
	{
		$data = array();
		
		$data['title'] 		= 'List Taxes';
		$data['sub_title'] 	= 'Manage';
		
		$dgClass 			= new dg();	
		
		$file = dirname(ROOT) .DS. 'data' .DS. 'taxes.json';
		if(!file_exists($file))
			$dgClass->writeFile($file, '{}');
		
		$taxes = file_get_contents($file);
		$taxes = json_decode($taxes);
		
		//sort array().
		$sort = array();
		if(count($taxes))
		{
			foreach($taxes as $key=>$val)
			{
				$count = 0;
				$vl = array();
				foreach($taxes as $k=>$v)
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
		
		// get admin info.
		$data['taxes']	= $sort;
		
		$this->view('taxes', $data);
	}
	
	public function Edit($id = '')
	{
		$dgClass 			= new dg();	
			
		$file = dirname(ROOT) .DS. 'data' .DS. 'taxes.json';
		if(!file_exists($file))
			$dgClass->writeFile($file, '{}');
		
		$taxs = file_get_contents($file);
		
		$taxs = json_decode($taxs);
		
		if(isset($_POST['data']) && count($_POST['data']))
		{	
			$data = array();
			
			if($id == '')
			{
				$post = new stdClass();
				$post->title = $_POST['data']['title'];
				$post->type = $_POST['data']['type'];
				$post->value = $_POST['data']['value'];
				$post->date = date('Y-m-d H:i:s');
				$post->published = $_POST['data']['published'];
				
				// add tax.
				$tax_id = 1;
				if(count($taxs))
				{
					foreach($taxs as $tax)
					{
						if($tax->id > $id)
							$tax_id = $tax->id;
					}
					$tax_id = $tax_id + 1;
					
					foreach($taxs as $key=>$tax)
					{
						$data[$key] = $tax;
					}
				}
				$post->id = $tax_id;
				$data[] = $post;
			}else
			{
				// edit tax.
				if(count($taxs))
				{
					foreach($taxs as $key=>$tax)
					{
						if($tax->id == $id)
						{
							$tax->title = $_POST['data']['title'];
							$tax->type = $_POST['data']['type'];
							$tax->value = $_POST['data']['value'];
							$tax->published = $_POST['data']['published'];
						}
						$data[$key] = $tax;
					}
				}else
				{
					$dgClass->redirect('index.php/taxes');
				}
			}
			
			if(count($data))
				$dgClass->writeFile($file, json_encode($data));
			
			$dgClass->redirect('index.php/taxes');
		}
		
		// view tax.
		$taxdata = array();
		$tax_view = new stdClass();
		$tax_view->id = '';
		$tax_view->title = '';
		$tax_view->type = 'p';
		$tax_view->date = '';
		$tax_view->value = '';
		$tax_view->published = '';
		if(count($taxs))
		{
			foreach($taxs as $tax)
			{
				if($tax->id == $id)
					$tax_view = $tax;
			}
		}
		
		include_once(ROOT.DS.'views/edit_tax.php');
	}
	
	public function publish($type = 'published', $id = '')
	{
		$dgClass 			= new dg();	
		
		$ids = array();
		$publish = 1;
		if($type == 'unpublished')
			$publish = 0;
		
		if($id != '')
		{
			$ids[] = $id;
		}elseif(isset($_POST['ids']))
		{
			$ids = $_POST['ids'];
		}
		
		if(count($ids))
		{
			$data = array();
			$file = dirname(ROOT) .DS. 'data' .DS. 'taxes.json';
			$taxs = file_get_contents($file);
			
			$taxs = json_decode($taxs);
			
			foreach($taxs as $key=>$tax)
			{
				if(in_array($tax->id, $ids))
					$tax->published = $publish;
				
				$data[$key] = $tax;
			}
			
			$dgClass->writeFile($file, json_encode($data));
		}
		
		$dgClass->redirect('index.php/taxes');
	}
	
	public function delete($id = '')
	{
		$dgClass 			= new dg();	
		
		$ids = array();
		if($id != '')
		{
			$ids[] = $id;
		}elseif(isset($_POST['ids']))
		{
			$ids = $_POST['ids'];
		}
			
		if(count($ids))
		{
			$data = array();
			$file = dirname(ROOT) .DS. 'data' .DS. 'taxes.json';
			$taxes = file_get_contents($file);
			
			$taxes = json_decode($taxes);
			
			foreach($taxes as $key=>$tax)
			{
				if(!in_array($tax->id, $ids))
					$data[$key] = $tax;
			}
			
			$dgClass->writeFile($file, json_encode($data));
		}
		
		$dgClass->redirect('index.php/taxes');
	}
}

?>