<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: November 26 2015
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
$data			= $GLOBALS['data'];

if (isset($data['print_type']))
{
	$result = $params['result'];
	$result->options[] = array(
		'name' => 'Printing type',
		'type' => 'printing',
		'value' => $data['print_type'],
	);
	$GLOBALS['result'] = $result;
}
?>