<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-03-05
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
if ( ! defined('ROOT') ) exit('No direct script access allowed');

class Printing extends Controllers
{	
	public function index()
	{
		$this->loaddata();
	}
	
	private function loaddata( $msg = '' )
	{
		$data['title'] 		= 'Printing';
		$data['breadcrumb'] = 'Printing';
		$data['sub_title'] 	= 'Manage';
		$printings 			= array();
		$file 				= dirname( ROOT ) .DS. 'data' .DS. 'printings.json';
		
		if ( file_exists( $file ) )
		{
			$content = file_get_contents( $file );
			if ( $content != false && $content != '' ) $printings = json_decode( $content, true );
		}
		
		function sortId( $a, $b )
		{
			$a = $a['id'];
			$b = $b['id'];
			return $b - $a;
		}
		usort( $printings, 'sortId' );
		
		if( !empty( $msg ) || $msg !== '' ) $data['msg'] = $msg;
		
		$data['printings']	= $printings;
		$this->view( 'printing', $data );
	}
	
	public function edit( $id = 0, $msg = '' )
	{
		$data['title'] 		= 'Printing';
		$data['breadcrumb'] = 'Printing';
		$data['sub_title'] 	= 'Manage';
		$data['types'] 		= $this->load_printings();
		
		if( $id > 0 )
		{
			$printings 		= array();
			$file 			= dirname( ROOT ) . DS . 'data' . DS . 'printings.json';
			if ( file_exists( $file ) )
			{
				$content = file_get_contents( $file );
				if ( $content != false && $content != '' ) $printings = json_decode( $content, true );
			}
			
			if ( count( $printings ) > 0 )
			{	
				foreach ( $printings as $value )
				{
					if ( $value['id'] == $id )
					{
						$obj 				= new stdclass();
						$obj->id 			= $id;
						$obj->title 		= $value['title'];
						$obj->printing_code = $value['printing_code'];
						if(array_key_exists('short_description', $value))
						{
							$obj->short_description = $value['short_description'];
						}
						$obj->description 	= $value['description'];
						if(array_key_exists('view', $value))
							$obj->view			= $value['view'];
						$obj->price_type 	= $value['price_type'];
						if(array_key_exists('price_extra', $value))
						{
							$obj->price_extra	= $value['price_extra'];
						}
						if ( array_key_exists( 'values', $value ) )
						{
							$obj->values = $value['values'];
							//$obj->enable = $this->check_enable_view( $value['values'] );
						}
						$data['printing'] 	= $obj;
					}
				}
			}
		}
		$data['msg'] = $msg;
		$this->view( 'printing_add', $data );
	}
	
	public function validate()
	{
		$ajax 		= 0;
		$printings 	= array();
		$file 		= dirname( ROOT ) . DS . 'data' . DS . 'printings.json';
		if ( file_exists( $file ) )
		{
			$content = file_get_contents( $file );
			if ( $content != false && $content != '' ) $printings = json_decode( $content, true );
		}
		
		$id = 0;
		if( isset( $_POST['printing_id'] ) ) $id = $_POST['printing_id'];
		
		$code_old = '';
		$printing_code = '';
		if ( isset( $_POST['printing_code'] ) ) $printing_code = $_POST['printing_code'];
		
		if ( ! empty( $printing_code ) && $printing_code != '' )
		{
			foreach ( $printings as $value )
			{
				if ( $id > 0 && $id == $value['id'] ) $code_old = $value['printing_code'];
				if ( empty( $code_old ) && $printing_code === $value['printing_code'] ) $ajax = 1;
			}
		}
		echo $ajax;
		return;
	}
	
	public function delete()
	{
		$dg 		= new dg();
		$printings 	= array();
		$file 		= dirname( ROOT ) . DS . 'data' . DS . 'printings.json';
		
		if ( file_exists( $file ) ) 
		{
			$content = file_get_contents( $file );
			if ( $content != false && $content != '' ) $printings = json_decode( $content, true );
		}
		
		$ids = array();
		if ( isset( $_POST['ids'] ) ) $ids = $_POST['ids'];
		
		if ( count( $ids ) > 0 )
		{
			foreach ( $printings as $key=>$value )
			{
				foreach ( $ids as $id )
				{
					if ( $value['id'] == $id ) unset( $printings[ $key ] );
				}
			}
		}
		$arr = array();
		foreach ( $printings as $value )
		{
			$arr[] = $value;
		}
		
		# Write data to json file
		$res = json_encode( $arr );
		$bres = $dg->WriteFile( $file, $res );
		$this->loaddata();
	}
	
	public function remove($id)
	{
		$dg 		= new dg();
		$printings 	= array();
		$file 		= dirname( ROOT ) . DS . 'data' . DS . 'printings.json';
		
		if ( file_exists( $file ) )
		{
			$content = file_get_contents( $file );
			if ( $content != false && $content != '' ) $printings = json_decode( $content, true );
		}
		
		foreach( $printings as $key=>$value )
		{
			if( $value['id'] == $id ) unset( $printings[ $key ] );
		}
		
		$write_arr = array();
		foreach( $printings as $value )
		{
			$write_arr[] = $value;
		}
		
		# Write to json
		$res 	= json_encode( $write_arr );
		$result = $dg->WriteFile( $file, $res );
		if( $result ) $this->loaddata();
		else $this->loaddata('Remove error.');
	}
	
	public function update() 
	{
		$dg 		= new dg();
		$bres 		= true;
		$printings 	= array();
		$file 		= dirname( ROOT ) . DS . 'data' . DS . 'printings.json';
		$get_id		= 0;
		if ( file_exists( $file ) ) 
		{
			$content = file_get_contents( $file );
			if ( $content != false && $content != '' ) $printings = json_decode( $content, true );
		} else fopen($file, 'w') or die("Data file does not exist. I can't create file, please contact with supporter.");
		
		if ( ! empty( $_POST ) ) 
		{
			$id = 0;
			if ( isset( $_POST['printing_id'] ) ) $id = $_POST['printing_id'];
			
			$title = '';
			if ( isset( $_POST['printing_title'] ) ) $title = $_POST['printing_title'];
			
			$printing_code = '';
			if ( isset( $_POST['printing_code'] ) ) $printing_code = $_POST['printing_code'];
			
			$price_type = '';
			if ( isset( $_POST['price_type'] ) ) $price_type = $_POST['price_type'];
			
			$printing_short_description = '';
			if ( isset( $_POST['printing_short_description'] ) ) $printing_short_description = $_POST['printing_short_description'];
			
			$printing_description = '';
			if ( isset( $_POST['printing_description'] ) ) $printing_description = $_POST['printing_description'];

			$printings_view = 0;
			if ( isset( $_POST['printings_view'] ) ) $printings_view = $_POST['printings_view'];
			
			$price_extra = 0;
			if ( isset( $_POST['price_extra'] ) ) $price_extra = $_POST['price_extra'];
			
			# diff from here
			$quantity_front = array();
			$prices_front = array();
			
			if ( isset( $_POST[ $price_type . '_quantity_front'] ) ) $quantity_front = $_POST[ $price_type . '_quantity_front'];
			if ( isset( $_POST[ $price_type . '_prices_front'] ) ) $prices_front = $_POST[ $price_type . '_prices_front'];
			
			$quantity_left 	= array(); $prices_left  = array();
			$quantity_right = array(); $prices_right = array();
			$quantity_back 	= array(); $prices_back  = array();
			
			if ( $printings_view == 0 )
			{
				$quantity_left  = $quantity_front;
				$quantity_right = $quantity_front;
				$quantity_back  = $quantity_front;
				
				$prices_left 	= $prices_front;
				$prices_right 	= $prices_front;
				$prices_back 	= $prices_front;
			}
			else # $printings_view == 1
			{
				if ( isset( $_POST[ $price_type . '_quantity_left'] ) ) $quantity_left = $_POST[ $price_type . '_quantity_left'];
				if ( isset( $_POST[ $price_type . '_quantity_right'] ) ) $quantity_right = $_POST[ $price_type . '_quantity_right'];
				if ( isset( $_POST[ $price_type . '_quantity_back'] ) ) $quantity_back = $_POST[ $price_type . '_quantity_back'];
				
				if ( isset( $_POST[ $price_type . '_prices_left'] ) ) $prices_left = $_POST[ $price_type . '_prices_left'];
				if ( isset( $_POST[ $price_type . '_prices_back'] ) ) $prices_back= $_POST[ $price_type . '_prices_back'];
				if ( isset( $_POST[ $price_type . '_prices_right'] ) ) $prices_right = $_POST[ $price_type . '_prices_right'];
			}
			
			$obj = new stdclass();
			if( $id == 0 ) 
			{ # insert action
				# Get id max from json
				$id_max = 0;
				foreach( $printings as $value )
				{
					if( $value['id'] > $id_max ) $id_max = $value['id'];
				}
				$id_max++;
				
				# Setting config price and quantity
				$values = new stdclass();
			
				$values->front = new stdclass();
				$values->front->quatity = $quantity_front;
				$values->front->prices  = $this->flip_arr( $prices_front );
				
				$values->left = new stdclass();
				$values->left->quatity = $quantity_left;
				$values->left->prices  = $this->flip_arr( $prices_left );
				
				$values->right = new stdclass();
				$values->right->quatity = $quantity_right;
				$values->right->prices  = $this->flip_arr( $prices_right );
				
				$values->back = new stdclass();
				$values->back->quatity = $quantity_back;
				$values->back->prices  = $this->flip_arr( $prices_back );	
				
				# Update to array json
				$obj->id 			= $id_max;
				$obj->title 		= $title;
				$obj->printing_code = $printing_code;
				$obj->price_type 	= $price_type;
				$obj->short_description = $printing_short_description;
				$obj->description 	= $printing_description;
				$obj->view			= $printings_view;
				$obj->price_extra	= $price_extra;
				$obj->values		= $values;
				$printings[] 		= $obj;
				
				$get_id				= $id_max;
			} 
			else # update action
			{
				$values = array(
					'front' => array('quatity' => $quantity_front,'prices' => $this->flip_arr($prices_front)),
					'left' 	=> array('quatity' => $quantity_left, 'prices' => $this->flip_arr($prices_left)),
					'right' => array('quatity' => $quantity_right,'prices' => $this->flip_arr($prices_right)),
					'back' 	=> array('quatity' => $quantity_back, 'prices' => $this->flip_arr($prices_back))
				);
				
				foreach( $printings as &$value )
				{
					if( $value['id'] == $id )
					{
						$get_id					= $id;
						$value['title'] 		= $title;
						$value['printing_code'] = $printing_code;
						$value['price_type'] 	= $price_type;
						$value['short_description'] = $printing_short_description;
						$value['description'] 	= $printing_description;
						$value['view']			= $printings_view;
						$value['price_extra']	= $price_extra;
						$value['values'] 		= $values;
						break;
					}
				}
			}
			
			# Write to json file
			$write 	= json_encode( $printings );
			$bres 	= $dg->WriteFile( $file, $write );
		}
		
		//if($bres) $this->loaddata();
		if($bres) $this->edit($get_id, '1');
		else $this->edit($get_id, '0');
	}
	
	private function flip_arr( $arr )
	{
		$out  = array();
		$rows = count( $arr );
		if ( $rows > 0 )
		{
			$cols = count( $arr[0] );
			for( $i = 0; $i < $rows; $i++ )
			{
				for( $j = 0; $j < $cols; $j++ )
				{
					$out[ $j ][ $i ] = $arr[ $i ][ $j ];
				}
			}
		}
		return $out;
	}
	
	public function load_views()
	{
		$dg 	= new dg();
		$views 	= array();
		$folder = dirname( ROOT ) . DS . 'adodns' . DS . 'printings' . DS . 'view' . DS;
		if ( is_dir( $folder ) )
		{
			if ( $handle = opendir( $folder ) )
			{
				while ( false !== ( $entry = readdir( $handle ) ) )
				{
					if( ! in_array( $entry, array(".","..") ) && file_exists( $folder . DS . $entry ) )
					{
						$files = str_replace( '.php', '', $entry );
					}
				}
			}
		}
	}
	
	public function load_printings() // private
	{
		$dg 			= new dg();
		$arr_printing 	= array();
		$folder 		= dirname( ROOT ) . DS . 'addons' . DS . 'printings' . DS;
		if ( is_dir( $folder ) )
		{
			# Count file .json on dir
			if ( $handle = opendir( $folder ) )
			{
				while ( false !== ( $entry = readdir( $handle ) ) )
				{
					if( ! in_array( $entry, array(".","..") ) && file_exists( $folder . DS . $entry ) )
					{
						$printing_file 	= str_replace( '.json', '', $entry );
						$file 			= $dg->readFile( $folder . DS . $entry );
						if ( $file != false )
						{
							$printing 					= json_decode( $file );
							$obj_printing 				= new stdclass();
							$obj_printing->id 			= $printing_file;
							$obj_printing->title 		= $printing->title;
							$obj_printing->description 	= $printing->description;
							$arr_printing[] 			= $obj_printing;
						}
					}
				}
			}
		}
		
		# Sort alphabe
		usort($arr_printing, array($this, 'sort_types'));

		return $arr_printing;
	}
	private function sort_types($a, $b)
	{
		$a = $a->title;
		$b = $b->title;
		return strcmp($a, $b);
	}
}