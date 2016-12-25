<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-03-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

if ( empty($_POST['print_type']) ) return '';

$print_type 	= $_POST['print_type'];

$file 			= ROOT .DS. 'data' .DS. 'printings.json';
if ( file_exists($file) )
{
	$content 	= file_get_contents($file);
	if ($content != false && $content != '')
	{
		$printings = json_decode($content);		
		if ( count($printings) )
		{
			foreach ($printings as $printing)
			{
				if ($printing->printing_code == $print_type)
				{
					if (isset($printing->description))
					{
						echo $printing->description;
						exit;
					}
				}
			}
		}
	}
}
?>