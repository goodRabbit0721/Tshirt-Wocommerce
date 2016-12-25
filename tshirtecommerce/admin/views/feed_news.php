<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-02-22
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

 $news = array();
 $i = 1;
 if(isset($data['news'])) { $news = $data['news']; }
 if(count($news) > 0) {
 ?>
<div class='clearfix visible-xs-block'></div></br></br>
<div class='col-md-8'>
	<div class='panel panel-default '>
		<div class='panel-heading feeds-panel'>
			<i class="fa fa-rss"></i>
			<strong><?php echo $addons->__('addon_feed_news_title_panel'); ?></strong>
			<a class='pull-right' href='javascript:void(0)' onclick='fnFeedNews(this)' style='color:#777;'>
				<i class="fa fa-chevron-up"></i>
			</a>
		</div>
		<div id='feednews-content'>
		<?php foreach($news as $feed) { ?>
		<div id='feeds-content' class='panel-body'>
			<a href='javascript:void(0)' class='feeds-title' onclick='showFeedNews(this)'>
					<?php echo $feed->title; ?>
			</a>
			<?php if($i == 1) { ?>
			<small class='feeds-date'><?php echo $feed->date; ?></small>
			<div class='feeds-content'><?php echo $feed->description; ?></div>
			<?php }else{ ?>
				<small class='feeds-date hidden'><?php echo $feed->date; ?></small>
				<div class='feeds-content hidden'><?php echo $feed->description; ?></div>
			<?php } $i++; ?>
		</div>
		<?php } ?>
		</div>
	</div>
</div>
<script>
	function fnFeedNews(e) {
		jQuery('#feednews-content').toggle('show');
		if(jQuery(e).find('.fa').hasClass('fa-chevron-up')) {
			jQuery(e).find('.fa').removeClass('fa-chevron-up');
			jQuery(e).find('.fa').addClass('fa-chevron-down');
		}
		else{
			jQuery(e).find('.fa').removeClass('fa-chevron-down');
			jQuery(e).find('.fa').addClass('fa-chevron-up');
		}
	}
	function showFeedNews(e) {
		jQuery(e).parent().parent().find('.feeds-date').each(function(){
			jQuery(this).addClass('hidden');
		});
		jQuery(e).parent().parent().find('.feeds-content').each(function(){
			jQuery(this).addClass('hidden');
		});
		jQuery(e).parent().find('.feeds-date').removeClass('hidden');
		jQuery(e).parent().find('.feeds-content').removeClass('hidden');
	}
</script>
 <?php } ?>