<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a banners module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	Banners Module
 */
class Banner_location_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
	}

	public function create($id, $input)
	{
		$status = array();

		if (count($input['pages']) > 0)
		{
			foreach($input['pages'] AS $page)
			{
				$page_record = $this->page_m->get_by('id', $page);

				$status[] = $this->insert(array('banner_id' => $id,
												'page_id' 	=> $page_record->id,
												'uri' 		=> $page_record->uri)
												);
			}
		}

		if ($input['urls'] > '')
		{
			$urls = explode("\n", $input['urls']);

			foreach($urls AS $url)
			{
				$status[] = $this->insert(array('banner_id' => $id,
												'uri' 		=> $url)
												);
			}
		}

		return in_array(FALSE, $status) ? FALSE : TRUE;
	}

	public function update_location($id, $input)
	{
		$this->delete_by('banner_id', $id);

		return $this->create($id, $input);
	}
}
