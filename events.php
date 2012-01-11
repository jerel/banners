<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a banners module for PyroCMS
 *
 * @author      Jerel Unruh - PyroCMS Dev Team
 * @website     http://unruhdesigns.com
 * @package     PyroCMS
 * @subpackage  Banners Module
 * @copyright   2012 by Jerel Unruh
 */
class Events_Banners {
    
    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        
        Events::register('post_page_edit', array($this, 'run'));
    }
    
    /**
     * This updates our stored uri with the 
     * page's new uri when a Page is edited
     *
     * @param 	$page_data mixed $_POST data
     * return 	void
    **/
    public function run($page_data)
    {
    	$page_id = $this->ci->uri->segment(4);
    	$this->ci->load->model('banners/banner_location_m');
        $this->ci->pyrocache->delete_all('banner_m');

        // get the newly formed uri
        $page = $this->ci->page_m->select('uri')->get_by('id', $page_id);
        
        $this->ci->banner_location_m->update_by('page_id', $page_id, array('uri' => $page->uri));
    }
    
}
/* End of file events.php */