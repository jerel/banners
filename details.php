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
		return "<h4>Overview</h4>
		<p>The Banners module is a very flexible tool for displaying images or text on specific pages 
		 or urls of your site. The Banners module is <strong>url driven</strong>. This means that it uses 
		 the visitor's location to determine which banner set to display. For simple usage you will select 
		 pages from the \"Select Pages\" field and their url location will be determined for you. If you 
		 want more control simply click \"Show Advanced Options\" and create your own url pattern rules.</p>
		<h4>Using the Single Tag</h4>
		<p>In its simplest form you may display an image set using the following tag: <strong>{{ banners:display }}</strong> <br />
		Usage: let's say that you have a page named \"Find Us\" and a page named \"Contact Us\" that both use the 
		default page layout. If you wanted to display an image of your business on both pages you could do this:</p> 
		<ul>
			<li>1. Create a banner set and give it a name. Select both pages from the \"Select Pages\" field</li>
			<li>2. Upload your image or images to the set</li>
			<li>3. Place the {{ banners:display debug=\"true\" }} tag in the Page Layout or in the theme layout file</li>
			<li>4. Reload the page and the image or images will be displayed</li>
			<li>5. When you are confident it is working properly remove the debug attribute. The cache will now 
				be refreshed only when you add or edit banners.
			</li>
		</ul>
		<p>You may pass attributes to the banners plugin to set the image width, debug mode, limit, etc. Below is 
		a single tag with all possible attributes:</p>
<pre style=\"width:97%\"><code>{{ banners:display 
    debug=\"true\" // add this attribute during development to turn off caching
    slug=\"header\" // if multiple sets are assigned a location you can select one by slug
    width=\"800\"  // set the image with. Only used in single tag mode
    height=\"250\" // set the image height. Only used in single tag mode
    mode=\"fill\"  // set the image crop mode. Only used in single tag mode.
    limit=\"5\"  // limit banner sets returned if multiple sets are assigned to a location
    image-limit=\"10\" // the maximum number of images to be returned per set
    order-by=\"name\" // order the sets by database column
    order-dir=\"desc\" // the direction to sort banner sets by: asc or desc
    image-order-by=\"sort\" // order images by database column
    image-order-dir=\"desc\" // the direction to sort images by: asc or desc
}}</code></pre>
		<h4>Using the Double Tag</h4>
		<p>You may use the double tag to do many more things not possible with the single tag. Since images are 
		optional you may even want to use a banner set to display nothing but text. This is very possible. This example 
		would display the text from the banner set's wysiwyg editor:<pre style=\"width:97%\"><code>{{ banners:display }}
    {{ text }}
{{ /banners:display }}</code></pre></p>
		<p>The double tag provides more functionality than the single tag, however since you are manipulating the 
		image url manually you must add the image width, height, and mode to the url instead of passing it as 
		an attribute. Below is a double tag with all possible attributes and common data fields:</p>
<pre style=\"width:97%\"><code>{{ banners:display 
    debug=\"true\" // add this attribute during development to turn off caching
    slug=\"header\" // if multiple sets are assigned a location you can select one by slug
    limit=\"5\"  // limit banner sets returned if multiple sets are assigned to a location
    image-limit=\"10\" // the maximum number of images to be returned per set
    order-by=\"name\" // order the sets by database column
    order-dir=\"desc\" // the direction to sort banner sets by: asc or desc
    image-order-by=\"sort\" // order images by database column
    image-order-dir=\"desc\" // the direction to sort images by: asc or desc
}}
    &lt;h4>{{ name }}&lt;/h4>
    &lt;div>{{ text }}&lt;/div>
    &lt;img src=\"{{ url:site }}files/large/{{ filename }}/800/300/fit\" alt=\"{{ description }}\"/>
{{ /banners:display }}</code></pre>
		<p>Or you can do special things with your images by using the double tag. For example:
<pre style=\"width:97%\"><code>{{ banners:display }}
    &lt;div class=\"slide-show\">
        &lt;img src=\"{{ url:site }}files/large/{{ filename }}/800/300\" alt=\"{{ description }}\"/>
    &lt;/div>
{{ /banners:display }}</code></pre>
		<p>The images for the Banners module are stored in the \"files\" table so all fields in that table are 
		available within the banners double tag. You will also notice that the folders and files are visible 
		via the Files interface.</p>
		<h4>Defining URL Patterns</h4>
		<p>You may define url patterns using regular expressions. This makes it easy to display a banner set on 
		certain types of urls without only choosing specific pages. Below are some simple examples using an asterisk to 
		specify a wildcard. (for you regex professionals the asterisk is converted to (.*?) before the expression is evaluated) 
<pre style=\"width:97%\"><code># the following line would display the banner set on the index page of the blog module
blog
# the following line would display the banner set on all of the blog module below the blog index page
blog/*
# this would display the banner set on all blog archives for the month of December in any year
blog/*/12/*
# Using raw regex: the following would display the set on any url with only numbers as the last segment
(.*?)/[0-9]+
# how about showing your handsome photo on every location that has your name in the url?
(.*?)?your-name(.*?)?</code></pre></p>";
	}
}
/* End of file details.php */
