<?php
/*
* Plugin Name: Tabbed Posts
* Plugin URI: https://tabbed-posts.klmnweb.com/
* Description: A responsive plugin to display blog posts in a tabbed format.
* Version: 1.0
* Author: KlmnWeb
* Text Domain: tabbed-posts
* Author URI: https://tabbed-posts.klmnweb.com/about/
* License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function klmnwb_tp_load_scripts()
{
		wp_register_script('tp-ui-js', plugin_dir_url(__FILE__) . 'jquery.overflowtabs.js', '', '', true);
		wp_register_style('tp-css', plugin_dir_url(__FILE__) . 'css.css', '', '', false);
		wp_register_style('tp-ui-css', plugin_dir_url(__FILE__) . 'style.css', '', '', false);
		wp_register_style('tp-jq-ui', plugin_dir_url(__FILE__) . 'jq-ui.css', '', '', false);
		//enqueuing the scripts and styles
		wp_enqueue_style('tp-css');
		wp_enqueue_style('tp-ui-css');
		wp_enqueue_style('tp-jq-ui');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('tp-ui-js');
    }
add_action('wp_enqueue_scripts', 'klmnwb_tp_load_scripts', 99);
function klmnwb_tp_sc($atts){
	ob_start();
	$allowed_html = array( 
	'div' => array(
        'class' => array(),
        'data' => array(),
        'style' => array(),
      ),
	);
	?>
	<div class="tab-area">
	<ul id="alltabs" class="tab-heads" role="tablist">
    <?php
	$atts = shortcode_atts( array(
	'cats' => '',
	'show_cat' => 'yes',
	'show_author' => 'yes',
	'show_date' => 'yes',
	'show_img' => 'yes',
	'posts_num'		=> 5,
	'nav_style' => 'advanced',
	'show_excerpt' =>'no',
	'date_format' => 'Y-m-d',
	'view_all' => 'no',
	), $atts, 'tabbed_posts' );
	$cats = explode(',' , $atts['cats']);
    $args = array(
        'hide_empty' => 1,
        'orderby' => 'name',
        'order' => 'ASC',
        'exclude' => 1,
        'include' => $cats,
    );
    $categories = get_categories($args);
	$count = 0;
    foreach ($categories as $category) {
		$count++;?>
     <li class="tab-switcher">
     <span onclick="clickTabHead(event, '<?php echo esc_js($category->slug);?>')"  class="catname <?php if($count==1){echo esc_js('active');}?>" role="tab" data-toggle="tab"><?php echo esc_js($category->name);?></span>
	 </li>
    <?php
    }?>
   </ul>
   <?php
    if ($atts['nav_style'] == "basic"){
		wp_dequeue_style('tp-ui-js');
		wp_dequeue_style('tp-jq-ui');
		wp_dequeue_script('jquery-ui-tabs');
		wp_dequeue_style('tp-ui-css');
		echo '<style type="text/css">#alltabs{white-space: nowrap;overflow-x: auto;overflow-y: hidden;}</style>';
		}
		?>
    <section class="post-tab-content">
    <?php
    foreach ($categories as $category) {
		?>
       <div class="post-tab-pane" id="<?php echo esc_html($category->slug);?>">
       <?php
       $the_query = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => $atts['posts_num'],
            'category_name' => $category->slug,
            'post_status' => 'publish'
        ));
		
        if ($the_query->have_posts()):
            $i = 0;
            while ($the_query->have_posts()):
                $the_query->the_post();
                if ($i == 0):
			if($atts['posts_num']>=7){echo wp_kses('<div class="wrapper-seven-posts">', $allowed_html);}
		?>
		<div class="<?php  
		if($atts['posts_num']==1){echo "tab-first-post-single";}
		elseif ($atts['posts_num']==2) {echo "tab-first-post-col2";}
		else {echo "tab-first-post";}?>"<?php if($atts['posts_num']>=7){echo 'style="max-width: 100%;"';}?>>
		<a class="image" href="<?php esc_url(the_permalink()); ?>">
        <?php if (has_post_thumbnail()){
               the_post_thumbnail(array(550,350 ));
		 }
		 ?>
		 </a>
            <h3><a href="<?php esc_url(the_permalink()); ?>"><?php echo esc_html(the_title()); ?></a></h3>
			<?php if ($atts['show_excerpt']==='yes'){
			$the_content = apply_filters('the_content', get_the_content()); ?>
			<?php if (!empty($the_content)){?>			
			<p class="post-exrpt"><?php echo wp_trim_words( esc_html(wp_strip_all_tags($the_content)), 20, '...'); ?></p>
			<?php }}?>
			<div class="tab-post-meta-single">
            <?php
                if ($atts['show_author'] != "no"){
				esc_html_e('By ', 'tabbed-posts');
				esc_url(the_author_posts_link());
				echo esc_html(' &#124; ');
				}
				if ($atts['show_cat'] != "no"){ 
				esc_html_e('in ', 'tabbed-posts');
				esc_url(the_category(' ')); 
				echo esc_html(' &#124; ');
				}
				if ($atts['show_date'] != "no"){ echo wp_date( $atts['date_format'], get_post_timestamp() ); }
				
			?>
		</div>
		</div>
	<?php 
	
	
	if ($atts['posts_num']==2){echo wp_kses('<div class="tab-siblings-col2">', $allowed_html);}
	elseif ($atts['posts_num']<7){echo wp_kses('<div class="tab-siblings">', $allowed_html);}
	else echo '';
	?>
	<?php elseif($i==1 && $atts['posts_num']>=7):?>
	   <div class="<?php  
	   if($atts['posts_num']==1){echo "tab-first-post-single";}
	   elseif ($atts['posts_num']==2) {echo "tab-first-post-col2";}
	   else {echo "tab-first-post";}?>"<?php if($atts['posts_num']>=7){echo 'style="max-width: 100%;"';}?>>
	   <a class="image" href="<?php esc_url(the_permalink()); ?>">
         <?php if (has_post_thumbnail()){
                    the_post_thumbnail(array(550,350 ));
		 }
		 ?>
           </a>
            <h3><a href="<?php esc_url(the_permalink()); ?>"><?php echo esc_html(the_title()); ?></a></h3>
		   <?php if ($atts['show_excerpt']==='yes'){
			$the_content = apply_filters('the_content', get_the_content()); ?>
			<?php if (!empty($the_content)){?>			
			<p class="post-exrpt"><?php echo wp_trim_words( esc_html(wp_strip_all_tags($the_content)), 20, '...'); ?></p>
			<?php }}?>
			<div class="tab-post-meta-single">
            <?php
                if ($atts['show_author'] != "no"){
				esc_html_e(' By ', 'tabbed-posts');
				esc_url(the_author_posts_link());
				echo esc_html(' &#124; ');
				}
				if ($atts['show_cat'] != "no"){ 
				esc_html_e(' in ', 'tabbed-posts');
				esc_url(the_category(' ')); 
				echo esc_html( ' &#124; ');
				}
				if ($atts['show_date'] != "no"){ echo wp_date( $atts['date_format'], get_post_timestamp() ); }
			?>
		</div>
		</div>
	   <?php if($atts['posts_num']>=7){echo '</div>';} ?>
		<div class="<?php  
		if($atts['posts_num']==2){echo "tab-siblings-col2";}
		else {echo "tab-siblings";}
		?>">
			<?php
                else:
		?>
		<div class="tab-child">
		<div class="tab-content">
		<div class="tab-image">
		<a href="<?php esc_url(the_permalink()); ?>"> <?php 
		if (has_post_thumbnail() && $atts['show_img'] != "no" && $atts['posts_num'] !=2){
                   the_post_thumbnail(array( 50, 50 ));
		  }
		  elseif (has_post_thumbnail() && $atts['show_img'] != "no" && $atts['posts_num'] ==2){ 
		   the_post_thumbnail(array( 550, 350 ));
		 }
		 else {
			 the_post_thumbnail(array( 50, 50 ));
		 }
		  ?>
          </a>
		  </div>
          <div class="tab-post-title">
			<h3><a href="<?php esc_url(the_permalink());?>" title="<?php esc_attr(the_title());?>"><?php echo esc_html(the_title());?></a></h3>
			<?php if($atts['show_excerpt']==='yes' && $atts['posts_num']==2){
			$the_content = apply_filters('the_content', get_the_content()); ?>
			<?php if (!empty($the_content)){?>			
			<p class="post-exrpt"><?php echo wp_trim_words( esc_html(wp_strip_all_tags($the_content)), 20, '...'); ?></p>
			<?php }}?>
			</div>
			</div>
			<div class="tab-post-meta">
            <?php 
				if ($atts['show_author'] != "no"){
				esc_html_e(' By ', 'tabbed-posts');
				esc_url(the_author_posts_link());
				echo esc_html(' &#124; ');
				}
				if ($atts['show_cat'] != "no"){ 
				esc_html_e(' in ', 'tabbed-posts');
				esc_url(the_category(' ')); 
				echo esc_html(' &#124; ');
				}
				if ($atts['show_date'] != "no"){  echo wp_date( $atts['date_format'], get_post_timestamp() ); }
			?>
           </div>
		   </div>
		<?php
                endif;
	?>
		<?php
                $i++;
            endwhile;
        endif;
		echo wp_kses_post(paginate_links());
        wp_reset_postdata();
?>
       </div>
	<?php 
	 if($atts['view_all']==='yes'):?>
	<div class="viewall">
	<a href="<?php echo esc_url(get_category_link($category->cat_ID)); ?>"><?php esc_html_e('View all', 'tabbed-posts');?></a>
	</div>
	<?php endif;?>
	</div><!---tab pane-->
        <?php
    }
?>
	</section><!---tab content-->
    </div><!--post tab pane-->
    <?php
    $output = ob_get_clean();
    return $output;
}
add_shortcode('tabbed_posts', 'klmnwb_tp_sc');
function klmnwb_tp_iniline_js(){
	ob_start();
	?>
	<script language="javascript" type="text/javascript">
	document.addEventListener("DOMContentLoaded", function () {
	var elems = document.getElementsByClassName('tab-heads');
	// display the navbar after the JS UI loaded
	for (var i=0;i<elems.length;i+=1){
	elems[i].style.display = 'block';
}
});	
	function clickTabHead(event, tabHead) {
		event.stopPropagation();
		var i, tabcontent, tablinks;
	tarea = event.target.closest('.tab-area');
	tabcontent = tarea.getElementsByClassName("post-tab-pane");	
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("catname");
		for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabHead).style.display = "block";
  event.currentTarget.className += " active";
}
</script>
<?php
$output = ob_get_clean();
echo $output;
}
add_action('wp_footer', 'klmnwb_tp_iniline_js');