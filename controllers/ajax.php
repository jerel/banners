<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * The inventory module is a website classifieds tool.
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Inventory Module
 * @category 	Addon Modules
 * @license 	proprietary
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
		return $this->banner_image_m->delete_image($this->input->post('id'),
											$this->input->post('gallery_id'));
	}
	
	//delete all images for this gallery
	public function delete_gallery_images()
	{
		return $this->banner_image_m->destroy_folder($this->input->post('id'));
	}
	
	//upload an image
	public function upload($gallery_id)
	{
		//load the necessary libraries
		$this->load->library('image_lib');
		$this->load->library('upload');

		if( is_int($id = $this->banner_image_m->upload($gallery_id)) )
		{
			//return json
			$data['status'] = 'success';
			$data['image_id'] = "$id";
			
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
			$this->banner_image_m->update_sort($id, $i);
			
			++$i;
		}
	}
	
	//update caption
	public function update_caption()
	{
		return $this->banner_image_m->update_caption($this->input->post('id'), $this->input->post('caption'));
	}
	
	//append an image to the manage page
	public function add_image()
	{
		$data['gallery_id'] = $this->input->post('gallery_id');
		
		$data['image'] = $this->banner_image_m->get_image($this->input->post('image_id'));
		
		echo $this->load->view('admin/partials/image_container', $data, TRUE);
	}
}
