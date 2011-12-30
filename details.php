<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Banners extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Banners'
			),
			'description' => array(
				'en' => 'This module allows the display of page banners for advertisement or display'
			),
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => 'design',
			'shortcuts' => array(
				array(
			 	   'name' => 'banners:add_banner',
				   'uri' => 'admin/banners/create',
				   'class' => 'add'
				),
				array(
			 	   'name' => 'banners:list_banners',
				   'uri' => 'admin/banners'
				),
			),
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('banners');

		$banners = array(
                        'id' => array(
									  'type' => 'INT',
									  'constraint' => '11',
									  'auto_increment' => TRUE
									  ),
						'name' => array(
										'type' => 'VARCHAR',
										'constraint' => '100',
										'default' => ''
										),
						'locations' => array(
										'type' => 'TEXT'
										),
						'folder_id' => array(
										'type' => 'INT',
										'constraint' => '11',
										'default' => 0
										)
						);

		$this->dbforge->add_field($banners);
		$this->dbforge->add_key('id', TRUE);

		if($this->dbforge->create_table('banners') AND
		   is_dir($this->upload_path.'files') OR @mkdir($this->upload_path.'files',0777,TRUE))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		if ($this->dbforge->drop_table('banners'))
		{
			return TRUE;
		}
	}


	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
	}
}
/* End of file details.php */
