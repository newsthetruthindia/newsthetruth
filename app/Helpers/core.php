<?php

$shortcodes = array();

function add_shortcode( $tag, $callback ) {
	global $shortcodes;
	if ( trim( $tag ) !=='' && 0 === preg_match( '@[<>&/\[\]\x00-\x20=]@', $tag ) ) {
		$shortcodes[ $tag ] = $callback;
	}
}

function shortcode_exists( $tag ) {
	global $shortcodes;
	return array_key_exists( $tag, $shortcodes );
}

function has_shortcode( $content, $tag ) {
	if ( false === strpos( $content, '[' ) ) {
		return false;
	}
	if ( shortcode_exists( $tag ) ) {
		preg_match_all( '/' . get_shortcode_regex() . '/', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) ) {
			return false;
		}
		foreach ( $matches as $shortcode ) {
			if ( $tag === $shortcode[2] ) {
				return true;
			} elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {
				return true;
			}
		}
	}
	return false;
}

function apply_shortcodes( $content, $ignore_html = false ) {
	return do_shortcode( $content, $ignore_html );
}

function do_shortcode( $content, $ignore_html = false ) {
	global $shortcodes;
	if ( false === strpos( $content, '[' ) ) {
		return $content;
	}
	if ( empty( $shortcodes ) || ! is_array( $shortcodes ) ) {
		return $content;
	}
	preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
	$tagnames = array_intersect( array_keys( $shortcodes ), $matches[1] );
	if ( empty( $tagnames ) ) {
		return $content;
	}
	$content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );
	$pattern = get_shortcode_regex( $tagnames );
	$content = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $content );
	$content = unescape_invalid_shortcodes( $content );
	return $content;
}

function get_shortcode_regex( $tagnames = null ) {
	global $shortcodes;
	if ( empty( $tagnames ) ) {
		$tagnames = array_keys( $shortcodes );
	}
	$tagregexp = implode( '|', array_map( 'preg_quote', $tagnames ) );
	// WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag().
	// Also, see shortcode_unautop() and shortcode.js.

	// phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound -- don't remove regex indentation
	return '\\['                             // Opening bracket.
		. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]].
		. "($tagregexp)"                     // 2: Shortcode name.
		. '(?![\\w-])'                       // Not followed by word character or hyphen.
		. '('                                // 3: Unroll the loop: Inside the opening shortcode tag.
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash.
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket.
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash.
		.     ')*?'
		. ')'
		. '(?:'
		.     '(\\/)'                        // 4: Self closing tag...
		.     '\\]'                          // ...and closing bracket.
		. '|'
		.     '\\]'                          // Closing bracket.
		.     '(?:'
		.         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags.
		.             '[^\\[]*+'             // Not an opening bracket.
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag.
		.                 '[^\\[]*+'         // Not an opening bracket.
		.             ')*+'
		.         ')'
		.         '\\[\\/\\2\\]'             // Closing shortcode tag.
		.     ')?'
		. ')'
		. '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]].
	// phpcs:enable
}

function do_shortcode_tag( $m ) {
	global $shortcodes;
	if ( '[' === $m[1] && ']' === $m[6] ) {
		return substr( $m[0], 1, -1 );
	}
	$tag  = $m[2];
	$attr = shortcode_parse_atts( $m[3] );
	if ( ! is_callable( $shortcodes[ $tag ] ) ) {
		return $m[0];
	}
	$content = isset( $m[5] ) ? $m[5] : null;
	$output = $m[1] . call_user_func( $shortcodes[ $tag ], $attr, $content, $tag ) . $m[6];
	return $output;
}

function do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames ) {
	$trans   = array(
		'&#91;' => '&#091;',
		'&#93;' => '&#093;',
	);
	$content = strtr( $content, $trans );
	$trans   = array(
		'[' => '&#91;',
		']' => '&#93;',
	);
	$pattern = get_shortcode_regex( $tagnames );
	$textarr = wp_html_split( $content );
	foreach ( $textarr as &$element ) {
		if ( '' === $element || '<' !== $element[0] ) {
			continue;
		}
		$noopen  = false === strpos( $element, '[' );
		$noclose = false === strpos( $element, ']' );
		if ( $noopen || $noclose ) {
			if ( $noopen xor $noclose ) {
				$element = strtr( $element, $trans );
			}
			continue;
		}
		if ( $ignore_html || '<!--' === substr( $element, 0, 4 ) || '<![CDATA[' === substr( $element, 0, 9 ) ) {
			$element = strtr( $element, $trans );
			continue;
		}
		$attributes = wp_kses_attr_parse( $element );
		if ( false === $attributes ) {
			if ( 1 === preg_match( '%^<\s*\[\[?[^\[\]]+\]%', $element ) ) {
				$element = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $element );
			}
			$element = strtr( $element, $trans );
			continue;
		}
		$front   = array_shift( $attributes );
		$back    = array_pop( $attributes );
		$matches = array();
		preg_match( '%[a-zA-Z0-9]+%', $front, $matches );
		$elname = $matches[0];
		foreach ( $attributes as &$attr ) {
			$open  = strpos( $attr, '[' );
			$close = strpos( $attr, ']' );
			if ( false === $open || false === $close ) {
				continue; // Go to next attribute. Square braces will be escaped at end of loop.
			}
			$double = strpos( $attr, '"' );
			$single = strpos( $attr, "'" );
			if ( ( false === $single || $open < $single ) && ( false === $double || $open < $double ) ) {
				
				$attr = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $attr );
			} else {
				$count    = 0;
				$new_attr = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $attr, -1, $count );
				if ( $count > 0 ) {
					$new_attr = wp_kses_one_attr( $new_attr, $elname );
					if ( '' !== trim( $new_attr ) ) {
						$attr = $new_attr;
					}
				}
			}
		}
		$element = $front . implode( '', $attributes ) . $back;
		$element = strtr( $element, $trans );
	}
	$content = implode( '', $textarr );
	return $content;
}

function unescape_invalid_shortcodes( $content ) {
	$trans = array(
		'&#91;' => '[',
		'&#93;' => ']',
	);
	$content = strtr( $content, $trans );
	return $content;
}

function get_shortcode_atts_regex() {
	return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|\'([^\']*)\'(?:\s|$)|(\S+)(?:\s|$)/';
}

function shortcode_parse_atts( $text ) {
	$atts    = array();
	$pattern = get_shortcode_atts_regex();
	$text    = preg_replace( "/[\x{00a0}\x{200b}]+/u", ' ', $text );
	if ( preg_match_all( $pattern, $text, $match, PREG_SET_ORDER ) ) {
		foreach ( $match as $m ) {
			if ( ! empty( $m[1] ) ) {
				$atts[ strtolower( $m[1] ) ] = stripcslashes( $m[2] );
			} elseif ( ! empty( $m[3] ) ) {
				$atts[ strtolower( $m[3] ) ] = stripcslashes( $m[4] );
			} elseif ( ! empty( $m[5] ) ) {
				$atts[ strtolower( $m[5] ) ] = stripcslashes( $m[6] );
			} elseif ( isset( $m[7] ) && strlen( $m[7] ) ) {
				$atts[] = stripcslashes( $m[7] );
			} elseif ( isset( $m[8] ) && strlen( $m[8] ) ) {
				$atts[] = stripcslashes( $m[8] );
			} elseif ( isset( $m[9] ) ) {
				$atts[] = stripcslashes( $m[9] );
			}
		}
		foreach ( $atts as &$value ) {
			if ( false !== strpos( $value, '<' ) ) {
				if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
					$value = '';
				}
			}
		}
	} else {
		$atts = ltrim( $text );
	}
	return $atts;
}

function shortcode_atts( $pairs, $atts, $shortcode = '' ) {
	$atts = (array) $atts;
	$out  = array();
	foreach ( $pairs as $name => $default ) {
		if ( array_key_exists( $name, $atts ) ) {
			$out[ $name ] = $atts[ $name ];
		} else {
			$out[ $name ] = $default;
		}
	}
	return $shortcode;
}

function strip_shortcodes( $content ) {
	global $shortcodes;
	if ( false === strpos( $content, '[' ) ) {
		return $content;
	}
	if ( empty( $shortcodes ) || ! is_array( $shortcodes ) ) {
		return $content;
	}
	preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
	$tags_to_remove = array_keys( $shortcodes );
	$tags_to_remove = apply_filters( 'strip_shortcodes_tagnames', $tags_to_remove, $content );
	$tagnames = array_intersect( $tags_to_remove, $matches[1] );
	if ( empty( $tagnames ) ) {
		return $content;
	}
	$content = do_shortcodes_in_html_tags( $content, true, $tagnames );
	$pattern = get_shortcode_regex( $tagnames );
	$content = preg_replace_callback( "/$pattern/", 'strip_shortcode_tag', $content );
	$content = unescape_invalid_shortcodes( $content );
	return $content;
}

function strip_shortcode_tag( $m ) {
	if ( '[' === $m[1] && ']' === $m[6] ) {
		return substr( $m[0], 1, -1 );
	}
	return $m[1] . $m[6];
}
