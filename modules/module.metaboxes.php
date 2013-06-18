<?php

/***********************************************************/
/*********PREPARE LANDING PAGE FORM META BOX ***************/
/***********************************************************/
/***********************************************************/

// Add additonal WYSIWYG edit box to landing page custom post type:
define('WYSIWYG_META_BOX_ID', 'lp_2_form_content');
define('WYSIWYG_EDITOR_ID', 'landing-page-myeditor'); //Important for CSS that this is different
define('WYSIWYG_META_KEY', 'lp-conversion-area');

/* ADD THUMBNAIL METABOX TO SIDEBAR */
add_action('add_meta_boxes', 'lp_display_thumbnail_metabox');
function lp_display_thumbnail_metabox() {

		add_meta_box( 
		'lp-thumbnail-sidebar-preview', 
		__( 'Template Preview', 'lp_metabox_thumbnail_preview' ),
		'lp_thumbnail_metabox',
		'landing-page' , 
		'side', 
		'high' );
}

function lp_thumbnail_metabox() {
	global $post;
	global $plugin_path;

	$template = get_post_meta($post->ID, 'lp-selected-template', true);
	$template = apply_filters('lp_selected_template',$template); 

	$permalink = get_permalink($post->ID);
	$datetime = the_modified_date('YmjH',null,null,false);
	$permalink = lp_ready_screenshot_url($permalink,$datetime);
	$thumbnail = 'http://s.wordpress.com/mshots/v1/' . urlencode(esc_url($permalink)) . '?w=250';
	$permalink = apply_filters('lp_live_screenshot_url', $permalink);
	?>
	<div >
		<div class="inside" style='margin-left:-8px;'> 
			<table>
				<tr>	
					<td>
						<?php						

							echo "<a href='$permalink' target='_blank' ><img src='$thumbnail' style='width:250px;height:250px;' title='Preveiw this theme ($template)'></a>";						
						?>
					</td>
				</tr>
			</table>
				
		</div>	
	</div>
	<?php
}		

/* ADD SPLIT TESTING METABOX TO SIDEBAR */
add_action('add_meta_boxes', 'lp_display_split_testing_metabox');
function lp_display_split_testing_metabox() {
		add_meta_box( 
		'lp-metabox-splittesting', 
		__( 'Split Testing', 'lp_metabox_split_testing_options' ),
		'lp_split_testing_metabox',
		'landing-page' , 
		'side', 
		'low' );
}

function lp_split_testing_metabox() {
	global $post;
     
        echo "<p style='font-weight:normal;'><strong>Please Note</strong> that this version 1 way of running Landing Page split tests will be phases out of the plugin soon.<br><br> Please use the <strong>new and improved A/B testing functionality</strong> located in the tabs above.";
        echo "<br><br><a href=\"#\" onClick=\"window.open('http://www.youtube.com/embed/KJ_EDJAvv9Y?autoplay=1','landing-page','width=640,height=480,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no')\">Watch Video Explanation</a></p>";
	echo '<p>
	<a name="lpsplittest" title="Landing Pages: Split Testing Options" id="lpopensplittestoptions" class="button-secondary thickbox" href="' . 	LANDINGPAGES_URLPATH.'modules/module.split-testing-splash.php?post_id=' . $post->ID . '&height=400&width=600&TB_iframe=true">Manage Split Testing</a> 
	<a name="lp-st-clone" title="Clone page and add it to the same split testing group" id="lp-st-clone-page-groupss" rel="'.$post->ID.'"  class="button-secondary thickbox"  href="' . 	LANDINGPAGES_URLPATH.'modules/module.split-testing-splash.php?post_id=' . $post->ID . '&clone=1&TB_iframe=true&height=400&width=600">Clone This Page</a>
	</p>';
	
}		

/* ADD CONVERSION AREA METABOX */
function lp_display_meta_box_lp_conversion_area(){
		add_meta_box( WYSIWYG_META_BOX_ID, __('Landing Page Form or Conversion Button', 'wysiwyg'), 'lp_meta_box_conversion_area', 'landing-page', 'normal', 'high' );
//add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
}

function lp_meta_box_conversion_area(){

		global $post;

		$meta_box_id = WYSIWYG_META_BOX_ID;
		$editor_id = WYSIWYG_EDITOR_ID;

		//Add CSS & jQuery goodness to make this work like the original WYSIWYG
		echo "
				<style type='text/css'>
						#$meta_box_id #edButtonHTML, #$meta_box_id #edButtonPreview {background-color: #F1F1F1; border-color: #DFDFDF #DFDFDF #CCC; color: #999;}
						#$editor_id{width:100%;}
						#$meta_box_id #editorcontainer{background:#fff !important;}
						#$meta_box_id #editor_id_fullscreen{display:none;}
				</style>

				<script type='text/javascript'>
						jQuery(function($){
								$('#$meta_box_id #editor-toolbar > a').click(function(){
										$('#$meta_box_id #editor-toolbar > a').removeClass('active');
										$(this).addClass('active');
								});

								if($('#$meta_box_id #edButtonPreview').hasClass('active')){
										$('#$meta_box_id #ed_toolbar').hide();
								}

								$('#$meta_box_id #edButtonPreview').click(function(){
										$('#$meta_box_id #ed_toolbar').hide();
								});

								$('#$meta_box_id #edButtonHTML').click(function(){
										$('#$meta_box_id #ed_toolbar').show();
								});

				//Tell the uploader to insert content into the correct WYSIWYG editor
				$('#media-buttons a').bind('click', function(){
					var customEditor = $(this).parents('#$meta_box_id');
					if(customEditor.length > 0){
						edCanvas = document.getElementById('$editor_id');
					}
					else{
						edCanvas = document.getElementById('content');
					}
				});
						});
				</script>
		";
		
		//Create The Editor
		$content = get_post_meta($post->ID, WYSIWYG_META_KEY, true);
		wp_editor($content, $editor_id);

		//Clear The Room!
		echo "<div style='clear:both; display:block;'></div>";
		echo "<div style='width:100%;text-align:right;margin-top:11px;'><div class='lp_tooltip'  title=\"To help track conversions Landing Pages Plugin will automatically add class='lp-track-form' to the first form element found in the conversion area. If there is no form element then it will automatically add class='lp-track-link' to the first link found in the conversion area. To track additional links and form elements the above class names may have to be added to elements manually.\" ></div></div>";
		
}

add_action('save_post', 'lp_wysiwyg_save_meta');
function lp_wysiwyg_save_meta(){

	$editor_id = WYSIWYG_EDITOR_ID;
	$meta_key = WYSIWYG_META_KEY;

	if(isset($_REQUEST[$editor_id]))
	{
		update_post_meta($_REQUEST['post_ID'], WYSIWYG_META_KEY, $_REQUEST[$editor_id]);
	}
}

/* ADD MAIN HEADLINE  
add_action( 'dbx_post_sidebar', 'lp_display_metabox_main_headline' );
function lp_display_metabox_main_headline() 
{
	global $post;
	if ($post->post_type=='landing-page')
	{
		$meta = get_post_meta($post->ID, 'lp-main-headline', true);
		$meta = str_replace('"','\"',$meta);
		$meta = apply_filters('lp_edit_main_headline', $meta, 1);
		echo '<div id="lp-main-headline-wrap">';
		if (isset($meta)&&$meta==null||!isset($meta))
		{
			echo '<label for="lp-main-headline" id="title-prompt-text" class="lp-main-headline-label">Primary Headline</label>';
		}
		else
		{
				echo '<label for="lp-main-headline" id="title-prompt-text" class="lp-main-headline-label"></label>';
		}
		echo '<input type="text" name="lp-main-headline" id="lp-main-headline" value="'.$meta.'" style="width:100%;height:31px;font-size: 1.7em;line-height: 100%;outline: 0 none;padding: 3px 8px;"  title="This headline will appear in the landing page template."></div>';
	}
}
*/
add_action( 'edit_form_after_title', 'lp_landing_page_header_area' );
add_action( 'save_post', 'lp_save_header_area' );
add_action( 'save_post', 'lp_save_notes_area' );

function lp_landing_page_header_area()
{
   global $post;
	$lp_variation = (isset($_GET['lp-variation-id'])) ? $_GET['lp-variation-id'] : '0';
	$main_title = get_post_meta( $post->ID , 'lp-main-headline', true );
	$varaition_notes = get_post_meta( $post->ID , 'lp-variation-notes', true );
    if ( empty ( $post ) || 'landing-page' !== get_post_type( $GLOBALS['post'] ) )
        return;

    if ( ! $main_title = get_post_meta( $post->ID , 'lp-main-headline',true ) )
        $main_title = '';

    if ( ! $varaition_notes = get_post_meta( $post->ID , 'lp-variation-notes',true ) )
        $varaition_notes = '';
	$main_title = apply_filters('lp_edit_main_headline', $main_title, 1);
	$varaition_notes = apply_filters('lp_edit_varaition_notes', $varaition_notes, 1);
		echo "<div id='lp-notes-area'>";
   		lp_display_notes_input('lp-variation-notes',$varaition_notes);
    	echo '</div><div id="main-title-area"><input type="text" name="lp-main-headline" placeholder="Primary Headline Goes here. This will be visible on the page" id="lp-main-headline" value="'.$main_title.'" title="This headline will appear in the landing page template."></div><div id="lp-current-view">'.$lp_variation.'</div><div id="switch-lp">0</div>';

}
function lp_save_header_area( $post_id )
{
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;

    $key = 'lp-main-headline';

    if ( isset ( $_POST[ $key ] ) )
        return update_post_meta( $post_id, $key, $_POST[ $key ] );

	//echo 1; exit;
    delete_post_meta( $post_id, $key );
}

function lp_save_notes_area( $post_id )
{
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;

    $key = 'lp-variation-notes';

    if ( isset ( $_POST[ $key ] ) )
        return update_post_meta( $post_id, $key, $_POST[ $key ] );

	//echo 1; exit;
    delete_post_meta( $post_id, $key );
}


add_filter( 'enter_title_here', 'lp_change_enter_title_text', 10, 2 );  
function lp_change_enter_title_text( $text, $post ) {  
	if ($post->post_type=='landing-page')
	{
        return 'Enter Landing Page Description';  
	}
	else
	{
		return $text;
	}
}  


/* ADD TEMPLATE SELECT METABOX  */

//Add select template meta box
function add_custom_meta_box_select_templates() { 
	
	add_meta_box(
		'lp_metabox_select_template', // $id
		__( 'Landing Page Templates', 'landingpage_custom_meta' ),
		'lp_display_meta_box_select_template', // $callback
		'landing-page', // $page
		'normal', // $context
		'high'); // $priority 
}

// Render select template box
function lp_display_meta_box_select_template() {
	//echo 1; exit; 
	global $post;

	$template =  get_post_meta($post->ID, 'lp-selected-template', true);
	$template = apply_filters('lp_selected_template',$template); 
	
	if (!isset($template)||isset($template)&&!$template){ $template = 'default';}
	
	$name = apply_filters('lp_selected_template_id','lp-selected-template');
	
	// Use nonce for verification
	echo "<input type='hidden' name='lp_lp_custom_fields_nonce' value='".wp_create_nonce('lp-nonce')."' />";
	?>
	
	<div id="lp_template_change"><h2><a class="button-primary" id="lp-change-template-button">Choose Another Template</a></div>
	<input type='hidden' id='lp_select_template' name='<?php echo $name; ?>' value='<?php echo $template; ?>'>
		<div id="template-display-options"></div>							
	
	<?php
}


add_action('admin_notices', 'lp_display_meta_box_select_template_container'); 	

// Render select template box
function lp_display_meta_box_select_template_container() {
	global $lp_data, $post,  $template_data_cats, $current_url;
	
	if (isset($post)&&$post->post_type!='landing-page'||!isset($post)){ return false; }
	
	( !strstr( $current_url, 'post-new.php')) ?  $toggle = "display:none" : $toggle = "";
	
	$template_data = lp_get_template_data();
	unset($template_data['lp']);

	$uploads = wp_upload_dir();
	$uploads_path = $uploads['basedir'];
	$extended_path = $uploads_path.'/landing-pages/templates/';

	$template =  get_post_meta($post->ID, 'lp-selected-template', true);
	$template = apply_filters('lp_selected_template',$template); 
	
	echo "<div class='lp-template-selector-container' style='{$toggle}'>";
	echo "<div class='lp-selection-heading'>";
	echo "<h1>Select Your Landing Page Template!</h1>"; 
	echo '<a class="button-secondary" style="display:none;" id="lp-cancel-selection">Cancel Template Change</a>';
	echo "</div>";
		echo '<ul id="template-filter" >';
			echo '<li><a href="#" data-filter="*">All</a></li>';
			$categories = array();
			foreach ($template_data_cats as $cat)
			{
				
				$slug = str_replace(' ','-',$cat['value']);
				$slug = strtolower($slug);
				$cat['value'] = ucwords($cat['value']);
				if (!in_array($cat['value'],$categories))
				{
					echo '<li><a href="#" data-filter=".'.$slug.'">'.$cat['value'].'</a></li>';
					$categories[] = $cat['value'];
				}
				
			}
		echo "</ul>";
		echo '<div id="templates-container" >';
		
		foreach ($template_data as $this_template=>$data)
		{
			 

			if (substr($this_template,0,4)=='ext-')
			{
				continue;
			}		

			$cat_slug = str_replace(' ', '-', $data['category']);
			$cat_slug = strtolower($cat_slug);
			// get demo link
			if (isset($data['features'][0]['url'])) 
				$demolink = $data['features'][0]['url'] . "?TB_iframe=true&width=1024&height=800"; // grab demo link
			else if ($this_template=='default')
				$demolink =  get_bloginfo('template_directory')."/screenshot.png";									
			else
				$demolink = "/wp-admin/customize.php?theme=" .$this_template. "&TB_iframe=true&width=1024&height=800";
			
			// get template description
			if (isset($data['features'][1]['label'])) 
				$template_desc = $data['features'][1]['label']; // grab demo link
			else if ($this_template=='default')
				$template_desc =  "This is your primary Wordpress theme that is currently active";								
			else
				// $shortname = $data['theme_slug'];
				$template_desc = "This is an inactive theme you have installed in your wordpress site";

			// Get Thumbnail
			if (isset($data['thumbnail']))
				$thumbnail = $data['thumbnail'];
			else if ($this_template=='default')
				$thumbnail =  get_bloginfo('template_directory')."/screenshot.png";									
			else
			{
				$thumbnail = LANDINGPAGES_UPLOADS_URLPATH.$this_template."/thumbnail.png";
			} 
			?>
			<div id='template-item' class="<?php echo $cat_slug; ?>">
				<div id="template-box">
					<div class="lp_tooltip_templates" title="<?php echo $template_desc; ?>"></div>
				<a class='lp_select_template' href='#' label='<?php echo $data['label']; ?>' id='<?php echo $this_template; ?>'>
					<img src="<?php echo $thumbnail; ?>" class='template-thumbnail' alt="<?php echo $data['label']; ?>" id='id_<?php echo $data['theme_slug']; ?>'>
				</a>
				<p>
					<div id="template-title"><?php echo $data['label']; ?></div>
					<a href='#' label='<?php echo $data['label']; ?>' id='<?php echo $this_template; ?>' class='lp_select_template'>Select</a> | 
					<a class='thickbox <?php echo $cat_slug;?>' href='<?php echo $demolink;?>' id='lp_preview_this_template'>Preview</a> 
				</p>
				</div>
			</div>
			<?php
		}
	echo '</div>';
	echo "<div class='clear'></div>";
	echo "</div>";
	echo "<div style='display:none;' class='currently_selected'>This is Currently Selected</a></div>";
/*	Add in first box for users to get more templates

echo '<div id="template-item" class="miscellaneous isotope-item special" style="">
				<div id="template-box">
					<div class="lp_tooltip_templates" oldtitle="The clean professional template is awesome!" title="" data-hasqtip="true"></div>
				<a class="lp_select_template" href="#" label="Clean Professional" id="clean-professional">
					<img src="http://inboundsoon.wpengine.com/wp-content/plugins/landing-pages/templates/clean-professional/thumbnail.png" class="template-thumbnail" alt="Clean Professional" id="id_">
				</a>
				<p>
					</p><div id="template-title">GET MORE Templates</div>
					<a href="#" label="Clean Professional" id="clean-professional" class="lp_select_template">Select</a> | 
					<a class="thickbox miscellaneous" href="../wp-content/plugins/landing-pages/templates/demo/demo2.html?TB_iframe=true&amp;width=1503&amp;height=429" id="lp_preview_this_template">Preview</a> 
				<p></p>
				</div>
			</div>
			<script>
			jQuery(document).ready(function ($) {
				var newItems = jQuery(".special");
				jQuery("#templates-container").prepend(newItems).isotope( "reloadItems" ).isotope({ sortBy: "original-order" });
			});
</script>'; */
}


/** 
 * PREPARE CUSTOM CSS META BOX FOR LANDING PAGES AND ADD CSS TO LP POST TYPE RENDERS
 */

// Custom CSS Widget
add_action('add_meta_boxes', 'add_custom_meta_box_lp_custom_css');
add_action('save_post', 'landing_pages_save_custom_css');

function add_custom_meta_box_lp_custom_css() {
   add_meta_box('lp_3_custom_css', 'Custom CSS', 'lp_custom_css_input', 'landing-page', 'normal', 'low');
}

function lp_custom_css_input() {
	global $post;
		
	echo "<em>Custom CSS may be required to remove sidebars, increase the widget of the post content container to 100%, and sometimes to manually remove comment boxes.</em>";
	echo '<input type="hidden" name="lp-custom-css-noncename" id="lp_custom_css_noncename" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	echo '<textarea name="lp-custom-css" id="lp-custom-css" rows="5" cols="30" style="width:100%;">'.get_post_meta($post->ID,'lp-custom-css',true).'</textarea>';
}

function landing_pages_save_custom_css($post_id) {
	global $post;
	if (!isset($post)||!isset($_POST['lp-custom-css']))
		return;
		
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	$lp_custom_css = $_POST['lp-custom-css'];
	update_post_meta($post_id, 'lp-custom-css', $lp_custom_css);
}


/** 
 * PREPARE CUSTOM JS META BOX FOR LANDING PAGES AND ADD CSS TO LP POST TYPE RENDERS
 *
 * Insert custom JS box to landing page
 * 
 */

add_action('add_meta_boxes', 'add_custom_meta_box_lp_custom_js');
add_action('save_post', 'landing_pages_save_custom_js');

function add_custom_meta_box_lp_custom_js() {
   add_meta_box('lp_3_custom_js', 'Custom JS', 'lp_custom_js_input', 'landing-page', 'normal', 'low');
}

function lp_custom_js_input() {
   global $post;
   echo "<em></em>";
   //echo wp_create_nonce('lp-custom-js');exit;
   echo '<input type="hidden" name="lp_custom_js_noncename" id="lp_custom_js_noncename" value="'.wp_create_nonce(basename(__FILE__)).'" />';
   echo '<textarea name="lp-custom-js" id="lp_custom_js" rows="5" cols="30" style="width:100%;">'.get_post_meta($post->ID,'lp-custom-js',true).'</textarea>';
}

function landing_pages_save_custom_js($post_id) {
	global $post;
	if (!isset($post)||!isset($_POST['lp-custom-css']))
		return;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	$lp_custom_js = $_POST['lp-custom-js'];
	update_post_meta($post_id, 'lp-custom-js', $lp_custom_js);
}


/********************************************************************************************************/
/*********PREPARE CUSTOM JS META BOX FOR LANDING PAGES AND ADD CSS TO LP POST TYPE RENDERS***************/
/********************************************************************************************************/
/********************************************************************************************************/

// Insert custom JS box
add_action('add_meta_boxes', 'add_custom_meta_box_lp_conversion_log');

function add_custom_meta_box_lp_conversion_log() {
   add_meta_box('lp_conversion_log_metabox', 'Lead Captures', 'lp_conversion_log_metabox', 'landing-page', 'normal', 'low');
}

function lp_conversion_log_metabox() {	
    
	
	class LP_LEAD_LOG extends WP_List_Table 
	{
		private $template_data;
		private $singular;
		private $plural;
		private $post;
		private $wpdb;
		function __construct()
		{
			global $post;
			global $wpdb;
	
			$query = "SELECT
				wposts.*
				FROM ".$wpdb->posts." AS wposts
				INNER JOIN ".$wpdb->postmeta." AS wpostmeta
				ON wpostmeta.post_id = wposts.ID
				AND wpostmeta.meta_key = 'wpleads_landing_page_{$post->ID}'
				WHERE wposts.post_type = 'wp-lead' ";
			$result = mysql_query($query);
			if (!$result){ echo $query; echo mysql_error();} 
			
			while($row = mysql_fetch_array($result))
			{		
				$this_data = array();
				$wplead_data = get_post_custom($row['ID']);
				//print_r($wplead_data);
				
				$conversion_data = $wplead_data['wpleads_conversion_data'][0];
				$conversion_data = json_decode($conversion_data,true);
				//print_r($conversion_data);
				//echo "<br>";
			
				$datetime = $conversion_data[$post->ID]['datetime'];
				(isset($conversion_data[$post->ID]['first_time'])) ? $first_time = 1 : $first_time = 0;

				//echo $first_time;
				//echo "<br>";
				
				//echo $datetime;
				if (isset($wplead_data['wpleads_email_address']))
				{
					$full_name = $wplead_data['wpleads_first_name'][0].' '.$wplead_data['wpleads_last_name'][0];
					$this_data['ID']  = $row['ID'];
					$this_data['date']  = $datetime;
					$this_data['name']  = $full_name;
					$this_data['email']  = $wplead_data['wpleads_email_address'][0];
					$this_data['first_time']  = $first_time;

					$this_data = apply_filters('lp_lead_table_data_construct',$this_data);
					
					$final_data[] = $this_data;
				}
			}
			//print_r($final_data);
			$this->table_data = $final_data; 			
			$this->singular = 'ID';
			$this->plural = 'ID';			
			
			//print_r($args);exit;
			$args['plural'] = sanitize_key( '' );
			$args['singular'] = sanitize_key( '' );
			$this->_args = $args;
		}
		
		function get_columns()
		{
			$columns = array(
			'date' => 'Date',
			'name' => 'Name',
			'email' => 'Email',
			'details' => 'Details', 
			);
			$columns = apply_filters('lp_lead_table_data_columns',$columns);
			return $columns;
		}
		
		
		function get_sortable_columns() 
		{
			$sortable_columns = array(
				//'template'  => array('template',false),
				//'category' => array('category',false),
				//'version'   => array('version',false)
			);
			
			$sortable_columns = apply_filters('lp_lead_table_data_sortable_columns',$sortable_columns);
			
			return $sortable_columns;
		}

		function usort_reorder( $a, $b ) 
		{
			// If no sort, default to title
			$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'date';
			// If no order, default to asc
			$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
			// Determine sort order
			$result = strcmp( $a[$orderby], $b[$orderby] );
			// Send final sort direction to usort
			//print_r($b);exit;
			//echo $order;exit;
			return ( $order === 'asc' ) ? $result : -$result;
		}

		function prepare_items() 
		{
			
			$columns  = $this->get_columns();


			$hidden = array('ID');
			$sortable = $this->get_sortable_columns();
			
			$this->_column_headers = array( $columns, $hidden, $sortable );
			if ($this->table_data)
				usort( $this->table_data, array( &$this, 'usort_reorder' ) );
			
			$per_page = 25;
			$current_page = $this->get_pagenum();
			
			$total_items = count( $this->table_data );

			if ($this->table_data)
				$this->found_data = array_slice( $this->table_data,( ( $current_page-1 )* $per_page ), $per_page );
			
			$this->set_pagination_args( array(
				'total_items' => $total_items,                  //WE have to calculate the total number of items
				'per_page'    => $per_page                     //WE have to determine how many items to show on a page
			) ); 
			 
			 
			$this->items = $this->found_data;
		}
		
		function column_default( $item, $column_name ) 
		{
			global $post;
			//echo $item[ 'first_time' ];
			switch( $column_name ) 
			{ 
				case 'date':
					return $item[ $column_name ];
				case 'name':
					return $item[ $column_name ];
				case 'email':
					return "<a href='mailto:".$item[ $column_name ]."'>".$item[ $column_name ]."</a>";
				case 'details':
					echo '<a href="' . 	LANDINGPAGES_URLPATH.'modules/module.lead-splash.php?lead_id=' . $item[ 'ID' ] . '&post_id=' . $post->ID . '&height=400&width=600&TB_iframe=true" class="thickbox">View Lead</a>';
					echo '&nbsp;&nbsp;';
					//print_r($item);
					if ($item[ 'first_time' ]==1)
					{
						echo '<img src="'.LANDINGPAGES_URLPATH.'images/new-lead.png" title="First timer!" style="float:right;">';
					}
					do_action('lp_lead_table_data_is_details_column',$item);
					return;		
			}
			
			do_action('lp_lead_table_data_add_column_listeners',$column_name);
			
		}
		
		function admin_header()  
		{
			//$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
			
			//if( 'lp_manage_templates' != $page )
			//return;
		}
	 
		function no_items() 
		{
			_e( 'No conversions recorded yet...' );
		}
		
		function get_bulk_actions() 
		{
			$actions = array(
			
			//	'upgrade'    => 'Upgrade',
			//	'delete'    => 'Delete',
			//	
			);
			
			return $actions;
		}

	}
	echo '<div id="leads-table-container">';
	echo '<h2>Conversion logs:</h2>'; 
	echo '<div id="leads-table-container-inside">';
	$myListTable = new LP_LEAD_LOG();	
	$myListTable->prepare_items();
	$myListTable->display();
	echo '</div>';
	echo '</div>';
}

//hook add_meta_box action into custom call fuction 
//lp_generate_meta is contained in functions.php
add_action('add_meta_boxes', 'lp_generate_meta');

?>