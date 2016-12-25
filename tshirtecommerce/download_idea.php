<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * API
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
error_reporting(0);

define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

if ( isset($_GET['key']) )
{
	include_once ROOT .DS. 'includes' .DS. 'functions.php';
	
	$key = $_GET['key'];
	$position = $_GET['view'];
	if ( isset($_GET['is_admin']) )
		$is_admin = 0;
	else
		$is_admin = $_GET['is_admin'];
	
	$dg = new dg();
	
	if ($is_admin == 0)
		$cache = $dg->cache('design');
	else
		$cache = $dg->cache('admin');
	
	$params = explode(':', $key);
	$user_id = $cache->get($params[0]);
	if ($user_id == false or count($user_id) == 0) return false;
	
	$data = $user_id[$params[1]];
		
	if ( count($data) > 0)
	{
		$product_id = $params[2];
		// get product
		$products = $dg->getProducts();
		for($i=0; $i<count($products); $i++)
		{
			if ($products[$i]->id == $product_id)
			{
				$product = $products[$i];
				break;
			}
		}
		
		if (isset($product))
		{
			$design = $product->design;
			if (count($design))
			{
				$files = array();
				
				if (isset($_GET['type']))
				{
					$file = $_GET['type'];
				}
				else
				{
					$file = 'svg';
				}
				
				// get size info of area design
				$zoom = 1;
				if (isset($product) && isset($product->design))
				{
					$design = $product->design;
					if ( isset($design->area) && isset($design->params) )
					{
						$area 		= json_decode( json_encode($design->area), true );
						$params 	= json_decode( json_encode($design->params), true );
						if ( isset($area[$position]) && isset($params[$position]) )
						{
							$sizes_cm = json_decode( str_replace("'", '"', $params[$position]), true );
							$sizes_px = json_decode( str_replace("'", '"', $area[$position]), true );
							
							if ($file == 'png')
								$zoom_inc = 0.393700787 * $sizes_cm['width'] * 300;
							else
								$zoom_inc = 0.393700787 * $sizes_cm['width'] * 90;
						
							$zoom = $zoom_inc / $sizes_px['width'];
							
						}
					}
				}
					
				
				$area = $design->area;
				if (isset($area->$position) && $area->$position != '')
				{
					$view 		= json_decode(str_replace("'", '"', $area->$position));	
					$radius 	= str_replace('px', '', $view->radius);						
					$svg 		= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="'.$view->height.'" width="'.$view->width.'" x="'.$view->left.'" y="'.$view->top.'">';
					$svgPNG 	= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="'.$view->height.'" width="'.$view->width.'" x="'.$view->left.'" y="'.$view->top.'">';
					
					// GET DESIGN OF ORDER
					$vectors 		= json_decode($data['vectors']);
					$items			= $vectors->$position;
					if (count($items) == 0)
					{
						echo 'Design is blank';
						exit;
					}
					
					$items			= json_decode ( json_encode($items), true);
					
					function cmp($a, $b)
					{
						return $a['zIndex'] - $b['zIndex'];
					}
					usort($items, 'cmp');
					
					$items			= json_decode ( json_encode($items) );
					
					foreach($items as $item)
					{
						
						$top 		= str_replace('px', '', $item->top);
						$left 		= str_replace('px', '', $item->left);
						
						$item->svg 	= str_replace('NS1:xmlns:xlink', '', $item->svg); 	// IF IE
						$item->svg 	= str_replace('xmlns:NS1=""', 'xmlns:xlink', $item->svg); 	// IF IE
						$item->svg 	= str_replace('xmlns:xml', 'xmlns:xlink', $item->svg); 	// IF IE
						$item->svg 	= str_replace('XML/1998/namespace', '1999/xlink', $item->svg); 	// IF IE
						
						$count = explode('xmlns="http://www.w3.org/2000/svg"', $item->svg);
						if (count($count) > 2)
						{
							$item->svg = $count[0]. 'xmlns="http://www.w3.org/2000/svg"';							
							for($iz=1; $iz < count($count); $iz ++)
							{
								$item->svg .= $count[$iz];
							}
						}						
						
						$count = explode('xmlns:xlink="http://www.w3.org/1999/xlink"', $item->svg);
						if (count($count) > 2)
						{
							$item->svg = $count[0]. ' xmlns:xlink="http://www.w3.org/1999/xlink"';
							for($iz=1; $iz < count($count); $iz ++)
							{
								$item->svg .= $count[$iz];
							}
						}						
						
						if ( isset($item->file) && isset($item->file->type) && $item->file->type == 'image' )
						{
							$item->svg 	= str_replace(' /></g>', '></image></g>', $item->svg); 	// IF IE
							$tempStr = explode('<image', $item->svg);
							$item->svg = $tempStr[0] . '<image'. str_replace('xmlns:xlink="http://www.w3.org/1999/xlink"', '', $tempStr[1]);
							
							preg_match_all("/xlink:href=\"(.*)\">/i", $item->svg, $links);
							
							if (isset($links[1][0]))
							{
								$link 	= str_replace('_thumb', '', $links[1][0]);
																
								if (isset($item->file_name) && isset($item->url))
								{
									if ($item->url != $item->thumb)
										$link = $item->url .'print/'. $item->file_name;
								}
								
								if (strpos($link, 'image/PNG;base64') === false)
								{
									if ($file == 'svg' && !empty($_GET['download']))
									{									
										$files[] = $link;
										$string = explode('/', $link);
										$file_name = $string[count($string)-1];									
										$temp = explode($links[1][0], $item->svg);
										if (isset($temp[1]))
										{
											$item->svg = $temp[0].$file_name.$temp[1];
										}									
									}
									else
									{
										$data 	= $dg->openURL($link);
										$base64 = 'data:image/PNG;base64,' . base64_encode($data);
										$temp = explode($links[1][0], $item->svg);
										if (isset($temp[1]))
										{
											$item->svg = $temp[0].$base64.$temp[1];
										}								
									}
								}
							}
						}									
						$dom = new DOMDocument();
						$dom->loadXML($item->svg);
						
						// team
						if($item->type == 'team')
						{
							$texts = $dom->getElementsByTagName("text");
							foreach ($texts as $text)
							{						
								if ($text->getAttribute('class') != '')
								{
									$action = '';
									if($text->getAttribute('class') == 'team-number')
									{
										$action = 'number';
									}
									else if($text->getAttribute('class') == 'team-name')
									{
										$action = 'name';
									}
																		
									if ($text->hasChildNodes() && $action != '')
									{
										foreach ($text->childNodes as $tspan) {
											if ($action == 'number')
											{
												$team_number = $tspan->nodeValue;
											}											
											else
											{
												$team_name = $tspan->nodeValue;
											}												
										}
									}
								}
							}
						}
						else if($item->type == 'text1')
						{							
							$texts = $dom->getElementsByTagName("text");
							$count_child = 0;
							$dy = 0;
							foreach ($texts as $text){
								$txt = array();
								if ($text->hasChildNodes()) {
									foreach ($text->childNodes as $tspan) {
										$txt[$count_child] = array();
										$txt[$count_child]['text'] = $tspan->nodeValue;
										$txt[$count_child]['x'] = $tspan->getAttribute('x');
										if ($tspan->getAttribute('dy') > $dy)
											$dy = $tspan->getAttribute('dy');
										$count_child++;										
									}
								}
							}
													
							
							foreach ($texts as $text){								
								$note = $text;
								$parent = $text->parentNode;
								for($i=0; $i< $count_child; $i++)
								{																		
									$elm = $dom->createElement('text', htmlentities($txt[$i]['text']));
									foreach ($note->attributes as $attr)
									{
										if ($attr->nodeName == 'y')
										{
											if ($dy == 0) $dy = $attr->nodeValue;
											$elm->setAttribute($attr->nodeName, $dy * ($i + 1) - ($i * 2));
										}
										else if($attr->nodeName == 'text-anchor')
										{
											//$elm->setAttribute($attr->nodeName, 'start');
										}
										else if($attr->nodeName == 'x')
										{
											if ($txt[$i]['x'] == '0')
											{
												$elm->setAttribute($attr->nodeName, 0);
												$elm->setAttribute('text-anchor', 'start');
											}
											else if ($txt[$i]['x'] == '50%')
											{
												$elm->setAttribute($attr->nodeName, '50%');
												$elm->setAttribute('text-anchor', 'middle');
											}
											else
											{
												$elm->setAttribute($attr->nodeName, '100%');
												$elm->setAttribute('text-anchor', 'end');
											}
										}
										else
										{
											$elm->setAttribute($attr->nodeName, $attr->nodeValue);
										}
									}
									$parent->appendChild($elm);
								}
								$text->parentNode->removeChild($text);
								break;								
							}						
						}						
						
						$x = $dom->getElementsByTagName("svg");
						foreach ($x as $xx){
							$xx->removeAttribute("x");
							$xx->removeAttribute("y");
						}									
						$item->svg = $dom->saveXML();					
						
						if (isset($item->rotate) && $item->rotate != 0)
						{
							$width 		= str_replace('px', '', $item->width);
							$height 	= str_replace('px', '', $item->height);
							$width		= (int) $width/2;
							$height		= (int) $height/2;

							$strsvg 	= str_replace('<svg ', '<svg ', $item->svg);
							
							
							$svgPNG 	.= '<g transform="translate('.($left*$zoom).', '.($top*$zoom).')  rotate('.$item->rotate.' '.($width*$zoom).' '.($height*$zoom).')">'.$strsvg.'</g>';							
							$svg 		.= '<g transform="translate('.$left.', '.$top.')  rotate('.$item->rotate.' '.$width.' '.$height.')">'.$strsvg.'</g>';
						}
						else
						{
							$svg 		.= str_replace('<svg ', '<svg y="'.$top.'" x="'.$left.'" ', $item->svg);
							if (isset($svgPNG))
								$svgPNG		.= str_replace('<svg ', '<svg y="'.$top.'" x="'.$left.'" ', $item->svg);
							else
								$svgPNG		= $svg;
						}
					}					
						
					// add mask
					if ( isset($product->download_mask) && $product->download_mask == 1)
					{
						$designProduct = $design->$position;
						$elms = str_replace("'", '"', $designProduct[0]);
						$elms = str_replace('["', '[', $elms);
						$elms = str_replace('"]', ']', $elms);
						$elms = json_decode($elms);
						
						
						if (isset($elms) && count($elms) > 0)
						{
							foreach($elms as $elm)
							{
								if (isset($elm->ismask) && $elm->ismask == 1)
								{
									$file_name = $elm->img;
									//echo '<pre>'; echo ($elm->width * $zoom); print_r($elm); exit;
									$svg .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="'.$elm->height.'" width="'.$elm->width.'" x="0" y="0">'
											.'<image xlink:href="'.$file_name.'" height="'.$elm->height.'" width="'.$elm->width.'" y="0" x="0"/>'
											.'</svg>';
											
									$svgPNG .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="'.$elm->height.'" width="'.$elm->width.'" x="0" y="0">'
											.'<image xlink:href="'.$file_name.'" height="'.$elm->height.'" width="'.$elm->width.'" y="0" x="0"/>'
											.'</svg>';
								}
							}
						}
					}
						
					$svg .= '</svg>';
					$svgPNG .= '</svg>';
					
					$svg 	= str_replace('<?xml version="1.0"?>', '', $svg);
					$svgPNG = str_replace('<?xml version="1.0"?>', '', $svgPNG);
										
		
					$setting 	= $dg->getSetting();					
					include_once(ROOT .DS. 'includes' .DS. 'addons.php');					
					$addons 	= new addons();
					$addons->view('hooks' .DS. 'download');
					
					if ($file == 'svg')
					{
						header('Content-type:image/svg+xml');						
						header('Content-Disposition: attachment; filename="'.$position.'.svg"');
					}
					else
					{
						$dom = new DOMDocument;
						
						$dom->loadXML($svgPNG);
						
						$books = $dom->getElementsByTagName('svg');
						foreach ($books as $book)
						{
							$width 	= $book->getAttribute('width') * $zoom;
							if ($width > 0)
								$book->setAttribute('width', $width);							
														
							$height 	= $book->getAttribute('height') * $zoom;
							if ($height > 0)
								$book->setAttribute('height', $height);							
							
							$x 	= $book->getAttribute('x') * $zoom;
							$book->setAttribute('x', $x);
							
							$y 	= $book->getAttribute('y') * $zoom;
							$book->setAttribute('y', $y);
						}
												
						$image 	= $dom->getElementsByTagName('image');							
						foreach ($image as $img)
						{
							$width 	= $img->getAttribute('width') * $zoom;							
							$img->setAttribute('width', $width);
							$height 	= $img->getAttribute('height') * $zoom;
							$img->setAttribute('height', $height);	
						}
						$svgPNG = $dom->saveXML();						
												
						echo $svgPNG;
					}
					exit;
				}
			}
			else
			{
				echo 'Design not found';
			}
		}
		else
		{
			echo 'Design not found';
		}
	}
	else
	{
		echo 'Design not found';
	}
}
else
{
	echo 'Design not found';
}
?>