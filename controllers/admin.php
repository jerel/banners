<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a banners module for PyroCMS
 *
 * @author 		Jerel Unruh - PyroCMS Dev Team
 * @website		http://unruhdesigns.com
 * @package 	PyroCMS
 * @subpackage 	Banners Module
 */
class Admin extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->model('banner_m');
		$this->load->model('banner_location_m');
		$this->load->library('form_validation');
		$this->lang->load('banners');

		// Set the validation rules
		$this->item_validation_rules = array(
			array(
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|max_length[100]|required'
			),
			array(
				'field' => 'text',
				'label' => 'Text',
				'rules' => 'trim'
			),
			array(
				'field' => 'pages',
				'label' => 'Pages'
			),
			array(
				'field' => 'urls',
				'label' => 'URLs',
				'rules' => 'trim'
			)
		);
	}

	public function index($offset = 0)
	{
		$limit = 25;

		$this->load->model('files/file_m');

		$data->banners = $this->banner_m->limit($limit)
			->offset($offset)
			->get_all();

		foreach ($data->banners AS &$banner)
		{
			$banner->image_count = $this->file_m->count_by('folder_id', $banner->folder_id);
		}

		$data->pagination = create_pagination('admin/banners/index', $this->banner_m->count_all(), $limit, 4);

		$this->template->title($this->module_details['name'])
						->build('admin/index', $data);
	}

	public function create()
	{
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);
		$banner->all_pages = $this->page_m->dropdown('id', 'title');

		if($this->form_validation->run())
		{
			// See if the model can create the record
			if(($id = $this->banner_m->create($this->input->post())) > 0)
			{
				// All good...
				$this->session->set_flashdata('success', lang('banners:create_success'));
				redirect('admin/banners/images/'.$id);
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('banners:create_error'));
				redirect('admin/banners/create');
			}
		}
		
		foreach ($this->item_validation_rules AS $rule)
		{
			$banner->{$rule['field']} = $this->input->post($rule['field']);
		}

		$data->banner = $banner;
		$this->template->title($this->module_details['name'], lang('banners:create'))
						->append_metadata($this->load->view('fragments/wysiwyg', NULL, TRUE))
						->build('admin/form', $data);
	}
	
	public function edit($id = 0)
	{
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);
		$banner = $this->banner_m->get_banner($id);
		$banner->all_pages = $this->page_m->order_by('uri')->dropdown('id', 'title');

		if($this->form_validation->run())
		{
			// See if the model can create the record
			if(($id = $this->banner_m->update_banner($id, $this->input->post())) > 0)
			{
				// All good...
				$this->session->set_flashdata('success', lang('banners:edit_success'));
				redirect('admin/banners');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('banners:edit_error'));
				redirect('admin/banners/edit/'.$id);
			}
		}

		$data->banner = $banner;
		$this->template->title($this->module_details['name'], lang('banners:edit'))
						->append_metadata($this->load->view('fragments/wysiwyg', NULL, TRUE))
						->build('admin/form', $data);
	}

	public function images($id = 0)
	{
		$this->load->model('banner_image_m');

		$data->banner 			= $this->banner_m->get($id);
		$data->banner->images 	= $this->banner_image_m->where('folder_id', $id)->get_all();

		$this->template->title($this->module_details['name'], lang('banners:edit'))
						->append_metadata(css('admin.css', 'banners'))
						->append_metadata(css('jquery.fileupload-ui.css', 'banners'))
						->append_metadata(js('jquery.fileupload-ui.js', 'banners'))
						->append_metadata(js('jquery.fileupload.js', 'banners'))
						->append_metadata(js('upload.js', 'banners'))
						->append_metadata(js('functions.js', 'banners'))
						->build('admin/images', $data);
	}
	
	public function delete($id = 0)
	{
		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			// pass the ids and let MY_Model delete the items
			$this->banner_m->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
			$this->banner_m->delete($id);
		}
		redirect('admin/banners');
	}
}
