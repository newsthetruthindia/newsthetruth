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
