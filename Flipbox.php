<?php
/**
 * Flipbox
 *
 * Main file for the plugin.
 * PHP version 7.3.4
 *
 * @author Shivam Ramdhani <shivamr@bsf.io>
 * @since  File available since Release 0.1
 */


/**
 * To Enqueue javascript file.
 */
function Flipbox_Enqueue_scripts() 
{
    wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'js/script.js');    
    
}
add_action('admin_enqueue_scripts', 'Flipbox_Enqueue_scripts');

/**
 * To Enqueue css file.
 */
function Flipbox_Enqueue_style()
{
    wp_enqueue_style('style', plugin_dir_url(__FILE__). 'style.css');
}
add_action('wp_enqueue_scripts', 'Flipbox_Enqueue_style');


/**
 * Creates a CPT - Flipbox.
 */
function Custom_Post_type()
{
    $labels = array(      
    'name'               => 'Flipbox',
    'singular_name'      => 'Flipbox', 
    'menu_name'          => 'Flipbox',
    'all_items'          => 'All Flipbox', 
    'view_item'          => 'View wow Flipbox',
    'add_new_item'       =>  false,
    'add_new'            => 'Add New Flipbox',
    'edit_item'          => 'Edit Flipbox',
    'update_item'        => 'Update Flipbox',
    'search_items'       => 'Search Flipbox',
    'not_found'          => 'Not found', 
    'not_found_in_trash' => 'Not Found in trash'
    );  

    $args = array(
    'label'                => 'Flipbox', 'twentynineteen',
    'description'          => 'Flipbox', 'twentynineteen',
    'labels'               => $labels,
    'hierarchial'          => false,                                                
    'public'               => true,
    'show_ui'               => true,
    'show_in_menu'         => true,
    'show_in_nav_menus'  => true,
    'show_in_admin_bar'  => true,
    'menu_position'         => 15,
    'can_export'          => true,
    'has_archive'        => true,
    'exclude_from_search'=> false,
    'publicly_queryable' => true,                                                 
    'capability_type'     => 'post',                                         
    'supports'              => ( array( 'title' ) ),                                       
    );
        
    register_post_type('Flipbox', $args);
}
add_action('init', 'Custom_Post_type', 0);

/**
 * Register meta box(es).
 */
function Flipbox_Add_description() 
{
    add_meta_box('meta-box-id', __('Create Flipbox', 'textdomain'), 'Display_description', 'Flipbox');
}
add_action('add_meta_boxes_flipbox', 'Flipbox_Add_description');
 
/**
 * Display the meta box.
 * 
 * @param object post object to access the id
 */
function Display_description( $post ) 
{
    // Display code/markup goes here. Don't forget to include nonces!
    ?>
        <div>
              <div>
                <p>
                    <label for="image_url"><strong>Image</strong></label>
                </p>
                <p>
                    <input placeholder="set an image" name="image_url" id="image_url" value="<?php echo get_post_meta($post->ID, "image_url_value", true); ?>" >

                </p>
                <p>
                    <input type="button" name="upload-button" id="upload-button-id" class="button-secondary" value="Upload Image">
                </p>
            </div>
        </div>
        <div>
            <p>
               <strong>Select Flip Transition</strong>
            </p>
            <p>
                <select name="transition" >
                    <option>Left-to-Right</option>
                    <option>Top-to-bottom</option>
                </select>
            </p>
        </div>
        <div>
            <p>
                <strong>Enter backside Text</strong>
            </p>
            <p>
                <textarea name="description" placeholder="Enter description here..." rows="5" cols="35"><?php echo get_post_meta($post->ID, "description_value", true); ?></textarea>
            </p>
        </div>
    <?php 
}
 
/**
 * To save the image url, description, transition in database.
 * 
 * @param post_id
 */
function Flipbox_Save_description( $post_id ) 
{
    // Save logic goes here. Don't forget to include nonce checks!
    $image_url = isset($_POST['image_url'])?trim($_POST['image_url']): "";
    $description = isset($_POST['description'])?trim($_POST['description']): "";
    $transition = isset($_POST['transition'])?trim($_POST['transition']): "";
    


    if (!empty($image_url) && ! empty($description)) {

        update_post_meta($post_id, 'image_url_value', $image_url);
        update_post_meta($post_id, 'description_value', $description);
        update_post_meta($post_id, 'transition_value', $transition);
    }
}

add_action('save_post', 'Flipbox_Save_description');


/**
 * Function - Display the metabox created using shortcode.
 * 
 * @param atts
 */
function Flip_On_Front_end( $atts )
{
    // var_dump( $atts );
    $image = get_post_meta($atts['id'], 'image_url_value', true);
    $backside = get_post_meta($atts['id'], 'description_value', true);
    $flip = get_post_meta($atts['id'], 'transition_value', true);
    $flip_class = '';

    if ($flip == 'Left-to-Right' ) {

        $flip_class = 'horizontal';
    } elseif ($flip == 'Top-to-bottom' ) { 

        $flip_class = 'vertical';
    }
    
    
    ?>
        <div class="flip-box" id="flip-id">
          <div class="flip-box-inner <?php echo( $flip_class ); ?>" id="inner-id">
            <div class="flip-box-front">
              <img src="<?php echo($image) ?>" alt="Smiley face" />
            </div>
            <div class="flip-box-back" id="inner-back">
              <div>
                  <?php echo $backside; ?>
              </div>
            </div>
          </div>
        </div>
    <?php
}
add_shortcode('FlipAnything', 'Flip_On_Front_end');

/**
 * To load wp media.
 */
function Load_Wp_Media_files() 
{
    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'Load_Wp_Media_files');

/**
 * Add metabox to to Flipbox CPT
 */
function Add_Shortcode_metabox() 
{
    add_meta_box('shortcode_metabox_id', 'Shordcode', 'Display_Shortcode_metabox', 'Flipbox');
}

add_action('add_meta_boxes_flipbox', 'Add_Shortcode_metabox');

/**
 * Callback funtion to display the shortcode metabox.
 */
function Display_Shortcode_metabox() 
{

    ?>
     <div>
        <?php echo "[FlipAnything id = ".get_the_id()."]"; ?>
     </div>
    <?php

}

add_filter('manage_edit-flipbox_columns', 'My_Extra_Flipbox_columns');

/**
 * Create column - shortcode.
 * 
 * @param columns
 */
function My_Extra_Flipbox_columns( $columns ) 
{

    $columns = array(
        'title' => 'Title',
        'shortcode' => 'Shortcode',
        'date' => 'Date',
    );
    return $columns;

}

add_action('manage_flipbox_posts_custom_column', 'My_Flipbox_Column_content', 10, 2);

/**
 * Content of new column shortcode 
 * 
 * @param column_name
 * @param post_id
 */
function My_Flipbox_Column_content( $column_name, $post_id ) 
{

    $shortcode = get_the_id();
    echo "[FlipAnything id = ".$shortcode."]";

}

/**
 * Removes slug
 */
function Remove_Custom_taxonomy() 
{
    remove_meta_box('slugdiv', 'Flipbox', 'side');

}

add_action('admin_menu', 'Remove_Custom_taxonomy');

