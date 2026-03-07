<?php

if( !function_exists( 'dpImageUploader') ) {
	function dpImageUploader( $atts = [] ) {
		
		$blockclass = !empty( $atts['blockclass'] ) ? ' '.$atts['blockclass'] : '';
		$imgclass = !empty( $atts['imgclass'] ) ? $atts['imgclass'] : '';
		$imgname = !empty( $atts['imgname'] ) ? $atts['imgname'] : '';
		$imgvalue = !empty( $atts['imgvalue'] ) ? $atts['imgvalue'] : [];
		$callback = !empty( $atts['callback'] ) ? 'data-cb="'.$atts['callback'].'"' : '';
		$type = !empty( $atts['type'] ) ? $atts['type'] : 'image';
		$isdelete = !empty( $atts['isdelete'] ) ? $atts['isdelete'] : false;
		if( !( $imgvalue instanceof App\Models\Media ) ){
			$imgvalue = getAttachmentById( $imgvalue );
		}
		$attachment = !empty( $imgvalue->id ) ? $imgvalue->id :'';
		$url = !empty( $imgvalue->url ) ? url($imgvalue->url) :'';
		$html = '<div class="dp-uploader dp_uploader dp-attachments-uploader' . $blockclass . '" '.$callback.' data-type="'.$type.'" data-target="'.$imgclass.'"' . (!empty( $url ) && $type == 'image' ? ' style="background-image:url(\''.$url.'\')"' : '') . '>'.
							($isdelete ? '<span class="delete-img-block delete_img_block"><i class="fa fa-trash"></i></span>':'').
							(!empty( $url ) && $type == 'video' ? '<div class="uploader-video"><video width="200" height="240"><source src="'.$url.'" type="video/mp4"></video></div>' : '').
							'<div class="upload-block">'.
								(empty($attachment)?'<div class="upload_block add"><i class="fa fa-plus"></i></div>':
								'<div class="upload_block remove"><i class="fa fa-minus"></i></div>').
							'</div>'.
							'<input type="hidden" name="' . $imgname . '" class="' . $imgclass . '" value="'. $attachment . '"/>'.
						'</div>';
		echo $html;
	}
}


if( !function_exists( 'getAttachmentById') ) {
	function getAttachmentById( $id = '' ) {
		if( empty( $id ) ) return [];
		$media = new App\Models\Media();
		return $media->where('id', $id)->first();
	}
}

if( !function_exists( 'get_image_srcset') ) {
	function get_image_srcset( $id = '' ) {
		if( empty( $id ) ) return [];
		$m_m = new App\Models\Media();
		$m_c = new App\Http\Controllers\MediaController();
		$file = $m_m->where('id', $id)->first();
		$srcset=[];
		if( !empty($file) ){
		    if(in_array($file['mimetype'], $m_c->img_types) ){
		        $ext = $file['extension'];
		        $name = $file['name'];
		        $year       = date('Y', strtotime($file['created_at']) );
                $month       = date('m', strtotime($file['created_at']) );
                $exact_path = 'uploads/'.$year.'/'.$month.'/';
		        if( !empty( $ext ) && !empty( $ext ) ){
		            $real_path = str_replace(  $name.'.'.$ext, '', $file['url'] );
		            foreach( $m_c->image_sizes as $key=>$size ){
		                if(isset($size[2]) && $size[2]){
		                    $ratio_name = $key.'__';
		                    $src = url($real_path.$name.'-'.$size[0].'x'.$size[1].'.'.$ext).' '.$size[0].'w';
		                }else{
		                    $src = url($real_path.$key.'__'.$name.'-'.$size[0].'x'.$size[1].'.'.$ext).' '.$size[0].'w';
		                }
		                
		                array_push($srcset, $src);
		            }
		        }
		    }
		    
		}
		return trim(implode(', ', $srcset) );
	}
}
/*
if( !function_exists( 'get_subcategories_by_id') ) {
	function get_subcategories_by_id( $id = '', $type = 'categories' ) {
		if( empty( $id ) ) return;
		$ci = & get_instance();
		$table_name = 'dp_'.$type;
		
		$q = 'SELECT * FROM '.$table_name. ' WHERE instr(concat(" ,",parent_id,","), concat(",",'.$id.',",")) > 1';
		
		return $ci->db->query($q)->result_array();
	}
}*/

/*function do_shortcode( $sc = '' ) {
	if( false === strpos( $sc, '[' ) ) {
    return $sc;
  }
  $sc = trim( $sc, "[]");
	$atts = explode( ' ', $sc );
	$shortcode = !empty( $atts[0] ) ? $atts[0] : '';
	if( empty( $shortcode ) ) return;
	array_shift( $atts );
	$args = [];
	if( !empty( $atts ) ){
		foreach( $atts as $val ){
			$ar = explode( '=', $val );
			if( !empty( $ar[0] ) ){
				$args[$ar[0]] = $ar[1];
			}
		}
	}
	return call_user_func( $shortcode, $args );
}*/