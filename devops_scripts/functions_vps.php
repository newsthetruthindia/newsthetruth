<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

add_shortcode('menu_cralwer', 'getMenuById');

function getMenuById($atts)
{
	$attributes = shortcode_atts(array(
		'id' => 4,
	), $atts);
}


function get_option($key = '')
{
	$option = new App\Models\Option();
	return $option->where('key', $key)->first();
}
function update_option($key = '', $value = '')
{
	$option = new App\Models\Option();
	return $option->where('key', $key)->update(['value' => $value]);
}

function get_posts_by_category($category_id = '', $limit = 10)
{
	$posts = new App\Models\Post();
	return $posts->where('category_id', $category_id)->limit($limit)->get();
}

function get_posts_by_id($post_id = '')
{
	$posts = new App\Models\Post();
	return $posts->where('id', $post_id)->first();
}

function optimize_image_on_upload($filePath) {
    try {
        if (!file_exists($filePath)) {
            return;
        }

        // Initialize ImageManager with the GD driver
        $manager = new ImageManager(new Driver());

        // Read image from file system
        $image = $manager->read($filePath);

        // Resize the image to a maximum width of 1200px while maintaining aspect ratio
        $image->scale(width: 1200);

        // Save the image back with 80% quality to reduce file size
        $image->save($filePath, 80);

        Log::info("Image optimized successfully: " . $filePath);
    } catch (\Exception $e) {
        // Fail gracefully so the post saving process isn't interrupted
        Log::error("Image optimization failed for " . $filePath . ": " . $e->getMessage());
    }
}
