<?php
if ($option['type'] == 'table')
{
	echo '<table><thead><tr>';
	
	if (isset($option['label']) && count($option['label']))
	{
		foreach($option['label'] as $label)
		{
			echo '<th>'.$label.'</th>';
		}
	}	
	
	echo '</tr></thead><tbody><tr>';
	
	if (isset($option['value']) && count($option['value']))
	{
		foreach($option['value'] as $options_line)
		{
			echo '<tr>';
			
			foreach($options_line as $options_vale)
			{
				echo '<td>'.$options_vale.'</td>';
			}
			
			echo '</tr>';
		}
	}
	
	echo '</tr></tbody></table>';						
}
?>