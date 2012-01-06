<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a banners module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	Banners Module
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

		$limit = $this->attribute('limit', 0);
		
		if ( ! $banners = $this->banner_m->get_banners($this->uri->uri_string(), $limit))
		{
			return '';
		}

		if ($this->content())
		{
			// return the data so they can work with it
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
					$output .= '<img src="'.site_url('files/large/'.$image['id']).'" alt="'.$image['name'].'" title="'.$image['description'].'"/>';
				}
			}

			return $output;
		}
	}
}

/* End of file plugin.php */