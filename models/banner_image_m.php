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
class Banner_image_m extends MY_Model {

	protected $_table = 'files';

	public function __construct()
	{		
		parent::__construct();

		$this->load->config('config', 'banners');
	}

	public function upload($folder_id)
	{
		$config['upload_path'] 		= UPLOAD_PATH . 'files/';
		$config['allowed_types'] 	= config_item('allowed_types', 'banners');
		$config['max_size'] 		= config_item('max_size');
		$config['encrypt_name'] 	= TRUE;

		$this->upload->initialize($config);

		if($this->upload->do_upload())
		{
			$data = $this->upload->data();
			$to_insert = array(
				'folder_id'		=> $folder_id,
				'user_id'		=> (int) $this->current_user->id,
				'type'			=> 'i',
				'name'			=> $data['raw_name'],
				'description'	=> '',
				'filename'		=> $data['file_name'],
				'extension'		=> $data['file_ext'],
				'mimetype'		=> $data['file_type'],
				'filesize'		=> $data['file_size'],
				'width'			=> (int) $data['image_width'],
				'height'		=> (int) $data['image_height'],
				'date_added'	=> now()
			);

			$this->insert($to_insert);

			return (int) $this->db->insert_id();
		}
		return $this->upload->display_errors('', '');
	}
	
	//delete the images for this banner
	public function delete_set($id)
	{
		$ids = array();
		$images = $this->where('folder_id', $id)->get_all();

		// no images to delete
		if (count($images) == 0)
		{
			return TRUE;
		}

		foreach ($images AS $image)
		{
			$ids[] = $image->id;
			@unlink(UPLOAD_PATH.'files/'.$image->filename);
		}

		return $this->delete_many($ids);
	}
	
	//delete a single image
	public function delete_image($id)
	{
		$image = $this->get($id);
		$this->delete($id);
		
		return unlink(UPLOAD_PATH.'files/'.$image->filename);
	}
}