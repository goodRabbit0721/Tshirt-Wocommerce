<?php
/*
'meta_key'    => '_customer_user',
	'meta_value'  => ,
	*/
$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => -1,
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
) ) );
$product_orders = array();
foreach ( $customer_orders as $customer_order ) {
	$order = wc_get_order( $customer_order );
	foreach( $order->get_items() as $item_id => $item ) {
		//echo $item['product_id'];
		//_is_campaign
		$product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item);
		$_is_campaign =  get_post_meta($product->id,'_is_campaign',true);
		$_post= get_post($product->id);
		
		if($_is_campaign&&$_post->post_author==get_current_user_id()){
			$_profit =  get_post_meta($product->id,'_profit',true);
			//$_profit =  get_post_meta($product->id,'_profit',true);
			
			
			if(isset($product_orders[$product->id])){
				$_product_order = $product_orders[$product->id];
				$data = array('qty'=>$_product_order['qty']+$item['qty'],'subtotal'=>$_product_order['subtotal']+$item['line_subtotal'],'profit'=>$_profit);
				$product_orders[$product->id]=$data;
			}else
			{
				$data = array('qty'=>$item['qty'],'subtotal'=>$item['line_subtotal'],'profit'=>$_profit);
				$product_orders[$product->id]=$data;
			}
			
		} 
		//print_r($product);
		//$_campaign_length = get_post_meta($product->id,'_campaign_length',true);
		
		//print_r($item['qty']);
	} 
}


$current_user = wp_get_current_user();
        if (!($current_user instanceof WP_User))
            return;
$args = array(
    'author'     =>  $current_user->ID,
    'post_type'  => 'product',
	'orderby'        => 'ID',
	'order'          => 'DESC',
	'hide_empty'     => 1,
	'depth'          => 1,
	'posts_per_page' => -1

);

$author_posts = get_posts( $args );
$_pf = new WC_Product_Factory();  

?>
<style>
	.status span.error_message{color: red;}
	
	.box{text-align: center;
        color: #C1C1C1;
    }
	.box_1{border-right: 1px solid #C1C1C1;}
	.box_3{border-left: 1px solid #C1C1C1;}
.box_number{
	
	color: #737373;
    font-size: 25px;
    font-weight: bold;
}
.box-status{text-align: center;     padding-top: 10px;}
.box-status a{display: inline;
    color: #A7A7A7;}
	
.box-status a+a:before{
  content: '| ';
}
</style>
<?php
$total = 0;
$profit = 0;
$number_orders = 0;
foreach($product_orders as $_total){
	$number_orders+=$_total['qty'];
	$total+=$_total['subtotal'];
	$profit+=($_total['subtotal']/100)* $_total['profit'];
}
setlocale(LC_MONETARY, 'en_US');
?>
<h2 style="padding: 0px;    margin: 0px;    padding-bottom: 20px;"><?php echo apply_filters( 'woocommerce_my_account_my_downloads_title', __( 'Your Campaigns', 'woocommerce' ) ); ?></h2>
<div class="panel panel-default">
  <div class="panel-body">
    <div class="row">
	  <div class="col-md-4 box_1 box">
			<span class="box_number"><?php echo $number_orders; ?></span>
			<div class="box_desc"><span>products ordered</span></div>
		</div>
	  <div class="col-md-4 box_2 box">
			<span class="box_number"><?php echo money_format('%.2n', $profit); ?></span>
			<div class="box_desc"><span>profit</span></div>
	  </div>
	  <div class="col-md-4 box_3 box">
			<span class="box_number"><?php echo money_format('%.2n', $total-$profit); ?></span>
			<div class="box_desc"><span>to be paid</span></div>
	  </div>
	</div>
	<?php 
	/*
	<div class="row">
	  <div class="col-md-4  ">
			
		</div>
	  <div class="col-md-4  box-status">
		<a href="#">Today</a> <a href="#">Yesterday</a> <a href="#">Active</a> <a href="#">All Time</a>
	  </div>
	  <div class="col-md-4  ">
	  </div>
	</div>
	*/
	?>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-body">
<table class="table table-striped">
<?php
foreach ($author_posts as $post) {
	$_product = $_pf->get_product($post->ID);
	$_is_campaign =  get_post_meta($_product->id,'_is_campaign',true);
	if(!$_is_campaign){continue;}
	
	$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	
	$d1=new DateTime();
	$d2=new DateTime($post->post_date);
	$diff=$d2->diff($d1);
	$days = $diff->days;
	$length = get_post_meta($_product->id,'_campaign_length',true);
	$day_remaining = $length-$days;
	$current_sold = 0;
	$current_profit = 0;
	if(isset($product_orders[$_product->id])){
		$current_sold  = $product_orders[$_product->id]['qty'];
		$current_profit  = ($product_orders[$_product->id]['subtotal']/100)* $product_orders[$_product->id]['profit'] ;
	}
		
	?>
	<tr>
		<td width="120"><img style="width: 120px" src="<?php echo $feat_image  ;?>" /></td>
		<td><a href="<?php echo esc_url( get_permalink($_product->id) ) ; ?>"><?php echo get_the_title($_product->id); ?></a></td>
		<td><?php echo $current_sold;?> /<?php echo get_post_meta($_product->id,'_sales_goal',true); ?><br> sold</td>
		<td><?php echo $day_remaining>0?$day_remaining:0; ?>d<br> remaining</td>
		<td><?php echo $current_profit>0?money_format('%.2n', $current_profit):'N/A'; ?><br> profit</td>
		<td></td>
	</tr>
	<?php
	
}
?>
</table>
  </div>
</div>
