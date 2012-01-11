<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a banners module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	Banners Module
 * @copyright	2012 by Jerel Unruh
 */
class Plugin_Banners extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 * 
	 * {{ banners:display limit="5" }}
	 *      {{ id }} {{ description }}
	 * {{ /banners:display }}
	 *
	 * @return	array
	 */
	function display()
	{
		$this->load->model('banners/banner_m');
		$this->load->model('banners/banner_location_m');

		$debug 				= $this->attribute('debug', FALSE);
		$width 				= $this->attribute('width');
		$height 			= $this->attribute('height');
		$mode 				= $this->attribute('mode');

		$limit 				= $this->attribute('limit', 5);
		$image_limit 		= $this->attribute('image-limit', 5);
		$order_by 			= $this->attribute('order-by', 'name');
		$order_dir 			= $this->attribute('order-dir', 'random');
		$image_order_by 	= $this->attribute('image-order-by', 'sort');
		$image_order_dir 	= $this->attribute('image-order-dir');

		$params = array(
			'uri' 				=> $this->uri->uri_string(), 
			'limit'				=> $limit, 
			'image_limit'		=> $image_limit, 
			'order_by'			=> $order_by, 
			'order_dir'			=> $order_dir, 
			'image_order_by'	=> $image_order_by, 
			'image_order_dir'	=> $image_order_dir
		);

		// let them work on their site without caching
		if ($debug)
		{
			$banners = $this->banner_m->get_banners($params);
		}
		else
		{
			// we're in production! fetch the data and cache it
			$banners = $this->pyrocache->model('banner_m', 'get_banners', array($params));
		}

		if ( ! $banners OR $this->content())
		{
			// return the data array so they can work with it
			return $banners;
		}
		else
		{
			$output = '';

			// so they just used the single tag, we'll output the images for them
			foreach ($banners AS $banner)
			{
				foreach ($banner->images AS $image)
				{
					$output .= '<img src="'.site_url('files/large/'.$image['filename'].'/'.$width.'/'.$height.'/'.$mode).'" alt="'.$image['name'].'" title="'.$image['description'].'"/>';
				}
			}

			return $output;
		}
	}
}

/* End of file plugin.php */