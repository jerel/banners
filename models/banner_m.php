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
		// first create a folder for the images
		$folder = array(
			'parent_id' => 0,
			'slug' => strtolower($input['name']),
			'name' => lang('banners:banners').': '.$input['name'],
			'date_added' => now()
			);

		$folder_id = $this->file_folders_m->insert($folder);

		// now create a banner with the same id as the folder
		$to_insert = array(
			'id'			=> $folder_id,
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
	 * Update Banner
	 * 
	 * update a banner set
	 *
	 * @param 	int 	$id 	The banner id
	 * @param 	array 	$input 	The sanitized post data
	 * @return 	int
	 */
	public function update_banner($id, $input)
	{
		$to_update = array(
			'name'			=> $input['name'],
			'text'			=> $input['text']
			);

		$id = (int) $this->update($id, $to_update);

		// now record the uri
		if ($id AND count($input['pages']) > 0 OR $input['urls'] > '')
		{
			if ( ! $this->banner_location_m->update_location($id, $input))
			{
				$id = FALSE;
			}
		}

		return $id;
	}

	/**
	 * Get Banner
	 * 
	 * get a banner to edit
	 *
	 * @param 	string 	$id 	The banner id
	 * @return 	mixed
	 */
	public function get_banner($id)
	{
		$page_array = array();
		$url_array 	= array();

		$banner = $this->get($id);

		// retrieve all locations that we know are pages
		$pages = $this->banner_location_m->where('page_id >', 0)
			->where('banner_id', $id)
			->get_all();
		
		// now all the url patterns
		$urls = $this->banner_location_m->where('page_id', 0)
			->where('banner_id', $id)
			->get_all();

		if ($pages)
		{
			// loop through the location records and save the page id
			foreach ($pages AS $page)
			{
				$page_array[] = $page->page_id;
			}
		}

		if ($urls)
		{
			// build an array of urls so we can implode them with newline
			foreach ($urls AS $url)
			{
				$url_array[] = $url->uri;
			}
		}

		$banner->pages = $page_array;
		$banner->urls = implode("\n", $url_array);

		return $banner;
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

	public function delete_many($ids)
	{
		foreach ($ids AS $id)
		{
			$this->delete($id);
		}
	}

	public function delete_banner($id)
	{
		$this->file_folders_m->delete_by('id', $id);
		$this->banner_image_m->delete_set($id);

		return $this->delete($id);
	}
}