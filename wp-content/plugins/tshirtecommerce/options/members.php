<?php
if ($option['name'] == 'members')
{
	echo '<table>'
	. 		'<thead>'
	. 			'<tr>'
	. 				'<th>Line 1</th>'
	. 				'<th>Line 2</th>'
	. 				'<th>Line 3</th>'
	. 				'<th>Line 4</th>'
	. 				'<th>Quantity</th>'
	. 			'</tr>'
	. 		'</thead>'
	. 		'<tbody>';

	for($ij=1; $ij<=count($option['value']['line1']); $ij++ )
	{								
		echo 		'<tr>'
		.			'<td>'.$option['value']['line1'][$ij].'</td>'											
		.			'<td>'.$option['value']['line2'][$ij].'</td>'											
		.			'<td>'.$option['value']['line3'][$ij].'</td>'											
		.			'<td>'.$option['value']['line4'][$ij].'</td>'											
		.			'<td>'.$option['value']['line5'][$ij].'</td>'											
		.		'</tr>';
	}

	echo 		'</tbody></table>';
}
?>