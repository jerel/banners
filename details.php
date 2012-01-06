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
			 	   'name' => 'banners:create',
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
		$this->dbforge->drop_table('banner_locations');

		$banners = array(
                        'id' => array(
									  'type' => 'INT',
									  'constraint' => '11',
									  'default' => 0
									  ),
						'name' => array(
										'type' => 'VARCHAR',
										'constraint' => '100',
										'default' => ''
										),
						'slug' => array(
										'type' => 'VARCHAR',
										'constraint' => '100',
										'default' => ''
										),
						'text' => array(
										'type' => 'TEXT'
										)
						);

		$this->dbforge->add_field($banners);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('banners');

		$banner_locations = array(
                        'id' => array(
									  'type' => 'INT',
									  'constraint' => '11',
									  'auto_increment' => TRUE
									  ),
						'banner_id' => array(
										'type' => 'INT',
										'constraint' => '11',
										'default' => 0
										),
						'page_id' => array(
										'type' => 'INT',
										'constraint' => '11',
										'default' => 0
										),
						'uri' => array(
										'type' => 'VARCHAR',
										'constraint' => '255',
										'default' => ''
										)
						);

		$this->dbforge->add_field($banner_locations);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('banner_locations');

		is_dir($this->upload_path.'files') OR @mkdir($this->upload_path.'files',0777,TRUE);

		return TRUE;
	}

	public function uninstall()
	{
		if ($this->dbforge->drop_table('banners') AND
			$this->dbforge->drop_table('banner_locations'))
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
