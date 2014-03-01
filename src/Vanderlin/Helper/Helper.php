<?php namespace Vanderlin\Helper;

use File;

class Helper {

	public static function test() {
		return "test";
	}

	public static function isSetInArray($array, $thing, $default = null) {
		return isset($array[$thing]) ? $array[$thing] : $default;
	}

	public static function to_permalink($str, $replacer = "-") { 

	    if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
	    $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
		$str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
	    $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $str);
	    $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
	    $str = preg_replace(array('`[^a-z0-9]`i','`['.$replacer.']+`'), $replacer, $str);
	    $str = strtolower( trim($str, $replacer) );
	    return $str;
	}

	public static function make_dir($path) { 
			
		// do we habe a types icon folder
		if( File::isDirectory($path) == false ) {
			File::makeDirectory($path, 0777, true);
		}
		
	}

	// -------------------------------------------------------------
	public static function num_to_abc($n) { 
		$abc = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
		return isset($abc[$n]) ? $abc[$n] : $n;
	}

	// -------------------------------------------------------------
	public static function truncate_str($str, $max=100, $append="...") { 
		$s = preg_replace('/\s+?(\S+)?$/', '', substr($str, 0, $max));
		if(strlen($str)>$max && $append!="") $s .= $append;
		return $s;
	}

	// -------------------------------------------------------------
	public static function saveImageForType($options, $object) {

		$options['path'] = Helper::isSetInArray($options, 'path', 'images');
		$options['file'] = Helper::isSetInArray($options, 'file', null);
		$options['file_prefix'] = Helper::isSetInArray($options, 'file_prefix', '');

		if($options['file'] != null) {

			$path = $options['path'];
			$file = $options['file'];

			if(File::exists($path.$object)) {
				File::delete($path.$object);
			}

			$org_name = $file->getClientOriginalName();
			$dot_pos  = strpos($org_name, ".");
			if($dot_pos !== false) $org_name = substr($org_name, 0, $dot_pos);
			
			$filename = "";

			if($options['file_prefix']!="") $filename .= $options['file_prefix']."_";
			$filename .= Helper::to_permalink($org_name);
			$filename .= ".".$file->getClientOriginalExtension();

			$object = $filename;

			// if we do not have the folder
			// make it and save it. 
			Helper::make_dir( $path );

			$file->move($path, $filename);
			
			return $filename;
		}

		return "";
	
	}
	// -------------------------------------------------------------


    public static function img_resize($path, $size) {
        $token = explode("assets/", $path);
        
        $url = $path;
        if(count($token) > 1) {
           $url = 'assets_resize/'.$size.'/'.$token[1];
        }
        return strpos($url, "http")===false ? asset($url) : $url;
    }
    
	// -------------------------------------------------------------
	public static function make_edit_link($options) {

		$edit = [
			'html_id'=> Helper::isSetInArray($options, 'html_id', ''),
			'link_title'=> Helper::isSetInArray($options, 'link_title', 'Edit Link'),
			'edit-class'=> Helper::isSetInArray($options, 'edit_class', 'edit-in-place'),
			'data-type'=> Helper::isSetInArray($options, 'data-type', 'text'),
			'data-title'=> Helper::isSetInArray($options, 'data-title', ''),
			'data-url'=> Helper::isSetInArray($options, 'data-url', ''),
			'route-name'=> Helper::isSetInArray($options, 'route-name', ''),
			'param-name'=> Helper::isSetInArray($options, 'param-name', ''),
			'object-id'=> Helper::isSetInArray($options, 'object-id', ''),
			'data-mode'=> Helper::isSetInArray($options, 'data-mode', 'popup'),
			'data-placement'=> Helper::isSetInArray($options, 'data-placement', 'top'),
		];

		$html = '<a data-mode="'.$edit['data-mode'].'" data-pk="'.$edit['object-id'].'" ';
		$html .= ' id="'.$edit['param-name'].'"';
		$html .= ' class="'.$edit['edit-class'].'"';
		$html .= ' data-type="'.$edit['data-type'].'"';
		$html .= ' data-placement="'.$edit['data-placement'].'"';
		$html .= ' data-title="'.$edit['data-title'].'"';
		$html .= ' name="'.$edit['param-name'].'"';
		
		if($edit['data-type']=="textarea") $html .= ' data-tpl="<textarea cols=90%></textarea>"';

		if($edit['data-url'] !="") $html .= ' data-url="'.$edit['data-url'].'"';
		if($edit['route-name'] !="") $html .= ' data-url="'.route($edit['route-name']).'/'.$edit['id'].'"';
		$html .= ' >'.$edit['link_title'].'</a>';
		echo $html;

/*
		<a href="#" class="edit-in-place" id="username" data-type="text" data-pk="1" data-url="/post" data-title="Enter username">
		  		{{ $story->title }}
			  	</a>*/

	}

}