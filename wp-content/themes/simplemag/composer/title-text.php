<?php 
/**
 * Free Title or Text
 * Page Composer Section
 *
 * @package SimpleMag
 * @since 	SimpleMag 2.0
**/
?>

<section class="wrapper home-section title-text">
	<?php 
	$content = get_sub_field( 'title_text_content' ); 
	$tag = get_sub_field( 'title_styling' );
	
	$content_styling = array (
		'theme_title' => '<header class="section-header"><div class="title-with-sep"><h2 class="title">' . $content . '</h2></div></header>',
		'heading_1' => '<h1>' . $content . '</h1>',
		'heading_2' => '<h2>' . $content . '</h2>',
		'bold_text' => '<b>' . $content . '</b>',
		'paragraph_text' => '<p>' . $content . '</p>',
	);
	
	// Loop through select options an output the result
	foreach ( $content_styling as $style => $value ) {
		if ( $tag == $style) { echo $value; }
	}
	?>
</section><!-- Title/Text -->