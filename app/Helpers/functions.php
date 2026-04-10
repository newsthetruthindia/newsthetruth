<?php

use Illuminate\Support\Facades\DB;

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
	if (!empty($key)) {
		$opt = $option->where('key', $key)->first();
		if (empty($opt)) {
			$opt = $option;
		}
		$opt->key = $key;
		$opt->value = $value;
		$opt->save();
		return $opt;
	}
}
function delete_option($key = '')
{
	$option = new App\Models\Option();
	if (!empty($key)) {
		$deletable = $option->where('key', $key)->first();
		if (!empty($deletable)) $deletable->delete();
	}
}
function footer_tag($limit = 10)
{
	return DB::table('tags')
		->select('id', 'title', 'slug')
		->limit($limit)
		->get();
}
function marquee_news_list($limit = 10)
{
	return DB::table('posts')
		->where('status', 'published')
		->where('visibility', 'public')
		->orderBy('created_at', 'DESC')
		->limit($limit)
		->get();
}

/**
 * Optimizes an image for the web using Intervention Image v3.
 * Resizes to 1200px max width and applies 80% JPEG compression.
 */
function optimize_image_on_upload(string $path)
{
    try {
        // Increase limits for processing large images on restricted VPS environments
        ini_set('memory_limit', '512M');
        set_time_limit(180);

        if (!file_exists($path)) {
            \Illuminate\Support\Facades\Log::warning("Image Optimization skipped: File not found at " . $path);
            return;
        }

        // Check for Intervention Image classes
        if (!class_exists('\Intervention\Image\ImageManager')) {
            \Illuminate\Support\Facades\Log::error("Image Optimization Failed: ImageManager class missing.");
            return;
        }

        // Use GD by default but wrap in fine-grained catch
        $manager = \Intervention\Image\ImageManager::gd();
        $image = $manager->read($path);

        // Auto-orient based on EXIF
        if (method_exists($image, 'orient')) {
            $image->orient();
        }

        // Resize down if width > 1200px
        if ($image->width() > 1200) {
            $image->scale(width: 1200);
        }

        // Encode as JPEG with 80% quality (Balanced "not crushed" quality)
        // We catch encoding errors specifically to avoid 500s on broken GD WebP/etc
        try {
            $encoded = $image->toJpeg(80);
            $encoded->save($path);
            \Illuminate\Support\Facades\Log::info("Image Optimized Successfully: " . $path);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Image Encoding Failed: " . $e->getMessage());
        }
        
    } catch (\Throwable $e) {
        // Log the error but NEVER throw it to avoid breaking the parent save process
        \Illuminate\Support\Facades\Log::error("Image Optimization Fatal Error (Caught): " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    }
}
