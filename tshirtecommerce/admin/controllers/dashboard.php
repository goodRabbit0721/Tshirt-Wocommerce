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

class Dashboard extends Controllers
{
	public function index()
	{	
		include_once(ROOT.DS.'includes'.DS.'functions.php');
		$dg = new dg();
		$data['breadcrumb'] = lang('breadcrumb_dashboard', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		
		# Get news from feed_news.json
		$file = 'http://tshirtecommerce.com/feednews/api.php';
		$feed = $dg->openURL($file);
		$feeds = array();
		if($feed !== false) $feeds = json_decode($feed);
		
		if(count($feeds) > 0)
		{
			function sortByDate($a, $b)
			{
				$a = strtotime($a->date);
				$b = strtotime($b->date);
				return $b - $a;
			}
			usort($feeds, 'sortByDate');
			
			# Get 5 first feeds only
			$count = 1;
			foreach($feeds as $key=>&$feed)
			{
				if($count <= 5) 
					$feed->description = $feed->description 
						. "<a href='". $feed->link ."' target='_blank'> Read More...</a>";
				else unset($feeds[$key]);
				$count++;
			}
			
			$data['news'] = $feeds;
		}
		$this->view('dashboard', $data);
	}
}

?>