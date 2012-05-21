<?php
if(!function_exists('doer_of_stuff'))
{
function doer_of_stuff() 
{
	return  new WP_Error('client', __("Please enter your website URL and your security key."));
}
}
if (!function_exists('object_to_array')):
function object_to_array($object)
{
	if( !is_object($object) && !is_array($object))
	{
		return $object;
	}
	if( is_object($object))
	{
		$object = get_object_vars($object);
	}
	return array_map('object_to_array', $object);
}
endif;
