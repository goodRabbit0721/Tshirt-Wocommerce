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

class Update extends Controllers
{
	public function index()
	{	
		$data['breadcrumb'] = lang('breadcrumb_update', true);
		$data['sub_title'] = lang('breadcrumb_manager', true);
		$this->view('update', $data);
	}
}

?>