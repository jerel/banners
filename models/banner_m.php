<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a banners module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	Banners Module
 */
class Banner_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
	}

	/**
	 * Create Banner
	 * 
	 * create a banner set
	 *
	 * @param 	array 	$input 	The sanitized post data
	 * @return 	int
	 */
	public function create($input)
	{
		$to_insert = array(
			'name'			=> $input['name'],
			'text'			=> $input['text']
			);

		$id = (int) $this->insert($to_insert);

		// now record the uri
		if ($id AND count($input['pages']) > 0 OR $input['urls'] > '')
		{
			if ( ! $this->banner_location_m->create($id, $input))
			{
				$id = FALSE;
			}
		}

		return $id;
	}

	/**
	 * Get Banners
	 * 
	 * get all banners for the current page/uri
	 *
	 * @param 	string 	$uri 	The current uri
	 * @param 	string 	$limit 	database limit
	 * @return 	mixed
	 */
	public function get_banners($uri, $limit)
	{
		$uri_ids = array();

		// they're on the home page so there is no uri, we'll need to get it ourselves
		if ($uri == '')
		{
			$home_page = $this->page_m->get_by('is_home', TRUE);
			$uri = $home_page->uri;
		}

		// fetch all the uri so we can regex them
		$uris = $this->banner_location_m->dropdown('id', 'uri');

		// check for matches
		foreach ($uris AS $key => $pattern)
		{
			// replace the shorthand * with the proper regex .*?
			$pattern = preg_replace('@(^|\/|\w)(\*)($|\/|\w)@ms', '\1(.\2?)\3', $pattern);

			if (preg_match('@^'.$pattern.'$@ms', $uri))
			{
				$uri_ids[] = $key;
			}
		}

		$banners = $this->select('*')
			->join('banner_locations', 'banner_id = banners.id')
			->where_in('banner_locations.id', $uri_ids)
			->get_all();

		if ($banners)
		{
			foreach ($banners AS &$banner)
			{	
				// we need to fetch the images as an array for Lex
				$banner->images = $this->db->where('folder_id', $banner->folder_id)
					->get('files')
					->result_array();
			}
		}

		return $banners;
	}
}