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
class Ajax extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->model('banner_image_m');
		$this->lang->load('banners');
	}
	
	//delete one image
	public function delete_image()
	{
		return $this->banner_image_m->delete_image($this->input->post('id'));
	}
	
	//delete all images for this banner
	public function delete_set()
	{
		return $this->banner_image_m->delete_set($this->input->post('id'));
	}
	
	//upload an image
	public function upload($banner_id)
	{
		//load the necessary libraries
		$this->load->library('image_lib');
		$this->load->library('upload');

		if( is_int($id = $this->banner_image_m->upload($banner_id)) )
		{
			//return json
			$data['status'] 	= 'success';
			$data['image_id'] 	= "$id";
			
			echo json_encode($data);
		}
		else
		{
			// $id is actually an error message
			echo json_encode(array('status' => $id));
		}
	}
	
	//drag and drop image sorting
	public function ajax_update_order()
	{
		$ids = explode(',', $this->input->post('order'));

		$i = 1;

		foreach ($ids as $id)
		{
			$this->banner_image_m->update($id, array('sort' => $i));
			
			++$i;
		}
	}
	
	//update description/caption
	public function update_description()
	{
		$id = $this->input->post('id');
		$desc = $this->input->post('description');

		return $this->banner_image_m->update($id, array('description' => $desc));
	}
	
	//append an image to the manage page
	public function add_image()
	{
		$data['banner_id'] = $this->input->post('banner_id');
		
		$data['image'] = $this->banner_image_m->get_by('id', $this->input->post('image_id'));
		
		echo $this->load->view('admin/partials/image_container', $data, TRUE);
	}
}
