<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a banners module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	Banners Module
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
	
	//delete the images and remove the folder for this banner
	public function destroy_folder($id)
	{
		$this->load->helper('file');
		
		delete_files(UPLOAD_PATH . 'banner/' . $id);

		rmdir(UPLOAD_PATH . 'banner/' . $id);
			
		return $this->db->delete('g_images', array('banner_id' => $id));
	}
	
	//delete a single image
	public function delete_image($id, $folder_id)
	{
		$image = $this->get($id);
		$this->delete($id);
		
		return unlink(UPLOAD_PATH . 'banner/' . $folder_id . '/' . $image->filename);
	}
	
	//create the image folders
	public function create_folders($folder_id)
	{
		return is_dir(UPLOAD_PATH . 'banner/' . $folder_id) OR mkdir(UPLOAD_PATH . 'banner/' . $folder_id , 0777, TRUE);
	}
	
	//update the sort order of the images
	public function update_sort($id, $i)
	{
		return $this->db->where('id', $id)
						->update('g_images', array('sort' => $i));
	}
	
	//update image caption
	public function update_caption($id, $caption)
	{
		return $this->db->where('g_images.id', $id)
						->update('g_images', array('`caption`' => $caption));
	}
	
	//fetch the last uploaded image
	public function get_image($image_id)
	{	
		return $this->db->get_where('g_images', array('id' => $image_id))
						->row();
	}
}
