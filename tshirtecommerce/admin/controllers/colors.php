<?php
/*
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-17-02
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
*/

if ( ! defined('ROOT') ) exit('No direct script access allowed');

class Colors extends Controllers 
{
	public function index() {}
	
	#functoion: import data from CSV
	public function import()
	{
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		$ajax = 'Import failed';
		$arr = array();
		
		# get data from colors.json
		$file = dirname(ROOT).DS.'data'.DS.'colors.json';
		$color = $dg->readFile($file);
		$colors = json_decode($color, true);
		if(isset($colors['colors']))
		{
			$data['colors'] = $colors['colors'];
		}
		else
		{
			$data['colors'] = array();
		}
		
		# get data from CSV file
		$file_uploaded_name = '';
		$csv = array();
		
		if (isset($_FILES["fileToImport"]))
		{
			# upload directory
			$upload_path = dirname(ROOT) .DS. 'uploaded' .DS. 'csv' . DS;
			
			# upload file
			require_once dirname(ROOT) .DS. 'includes' .DS. 'upload.php';
			$uploader   =   new Uploader();
			$uploader->imageSeq = 'import-colors';
			$uploader->setDir($upload_path);

			$uploader->setExtensions(array('csv'));
			$uploader->setMaxSize(10);
			$uploader->sameName(false);
			if($uploader->uploadFile('fileToImport'))
			{
				$ajax = 'ok';
				$file_uploaded_name = $uploader->getUploadName();
				
				# get data from csv uploaded
				# Set path to CSV file
				$csvFile = $upload_path . $file_uploaded_name;

				$csv = $this->readCSV($csvFile);
			}
		}
		
		# unset bool(false/true) from csv array
		foreach($csv as $key=>$value)
		{
			if(!is_array($value)) unset($csv[$key]);
		}
		
		# set key for csv array
		$csv_arr = array();
		foreach($csv as $value)
		{
			$row = array();
			$row['title'] = $value[0];
			$row['hex'] = str_replace('#', '', $value[1]); # remove '#' characters from hex value
			
			$csv_arr[] = $row;
		}
		
		# append data to JSON array above
		$arr = array_merge($data['colors'], $csv_arr);
		
		# removes duplicate values from an array
		# $result = array_unique($arr, SORT_REGULAR); # only PHP version >= 5.2.9
		$result = $this->unique_multidim_array($arr, 'hex'); # for all PHP version
		var_dump($result);
		
		# write JSON file again
		$res = array();
		$res['status'] = "1";
		$res['colors'] = $result;
		$write = json_encode($res);
		$bres = $dg->WriteFile($file, $write);
		if($bres) $ajax = 'Import success.';
		
		# return message
		echo $ajax;
		return;
	}
	
	# function: removes duplicate values from an multidimensional array
	private function unique_multidim_array($array, $key) 
	{
		$temp_array = array();
		$i = 0;
		$key_array = array();
	   
		foreach($array as $val) 
		{
			if (!in_array($val[$key], $key_array)) 
			{
				$key_array[$i] = $val[$key];
				$temp_array[$i] = $val;
			}
			$i++;
		}
		return $temp_array;
	}  
	
	# function: read data from CSV file
	private function readCSV($csvFile)
	{
		$file_handle = fopen($csvFile, 'r');
		while (!feof($file_handle)) 
		{
			$line_of_text[] = fgetcsv($file_handle, 1024);
		}
		fclose($file_handle);
		return $line_of_text;
	}
	
	# function: export data to CSV
	public function export()
	{
		$dg = new dg();
		
		# set CSV name
		$file_name = 'colors-export.csv';
		
		# get data from colors.json to array
		$file = dirname(ROOT).DS.'data'.DS.'colors.json';
		$color = $dg->readFile($file);
		$colors = json_decode($color, true);
		if(isset($colors['colors']))
		{
			$data['colors'] = $colors['colors'];
		}
		else
		{
			$data['colors'] = array();
		}
		
		# sort array().
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
		
		# insert '#' to hexa (#000000)
		foreach($data['colors'] as &$value)
		{
			$hex = '#' . $value['hex'];
			$value['hex'] = $hex;
		}
		
		# output csv file
		$this->outputCSV($data['colors'], $file_name);
	}

	# function: output to CSV file
	private function outputCSV($data, $file_name = 'filename.csv') 
	{
		# output headers so that the file is downloaded rather than displayed
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=$file_name");
        # Disable caching - HTTP 1.1
        header("Cache-Control: no-cache, no-store, must-revalidate");
        # Disable caching - HTTP 1.0
        header("Pragma: no-cache");
        # Disable caching - Proxies
        header("Expires: 0");
    
        # Start the ouput
        $output = fopen("php://output", "w");
        
         # Then loop through the rows
        foreach ($data as $row) 
		{
            # Add the rows to the body
            fputcsv($output, $row); // here you can change delimiter/enclosure
        }
		
        # Close the stream off
        fclose($output);
		exit;
	}
}
 
 ?>
