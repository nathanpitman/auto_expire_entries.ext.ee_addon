<?php

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}

/*
=============================================================
	Auto Expire Entries
	- Nathan Pitman, www.ninefour.co.uk/labs
-------------------------------------------------------------
	Copyright (c) 2010 Nathan Pitman
=============================================================
	File:			ext.auto_expire_entries.php
=============================================================
	Version:		1.0.0
-------------------------------------------------------------
	Compatibility:	EE 1.6.x
-------------------------------------------------------------
	Purpose:		Auto expires all entries for a specified
					weblog after a specified number of days.				
=============================================================
*/

class Auto_expire_entries
{
	
    var $settings        = array();
    var $name            = 'Auto Expire Entries';
    var $version         = '1.0';
    var $description     = 'Auto expires all entries for a specified weblog after a specified number of days';
    var $settings_exist  = 'n';
    var $docs_url        = 'http://www.ninefour.co.uk';
    
    // -------------------------------
    //   Constructor - Extensions use this for settings
    // -------------------------------
    
    function Auto_expire_entries($settings='')
    {
        $this->settings = $settings;
    }
    // END

	 function set_expiration_date ($entry_id, $data)
	 {
		global $DB, $LOC;
		
		if ($data['weblog_id']==2) {
				
			$increment = 30;
			$new_expiration_date = ($data['entry_date']+($increment * 60 * 60 * 24));
			$item = array('expiration_date' => $new_expiration_date);
			
			$DB->query($DB->update_string("exp_weblog_titles", $item, "entry_id = ".$entry_id));	
					
		}
	 }
	 
// --------------------------------
//  Activate Extension
// --------------------------------

function activate_extension()
{
    global $DB;
    
    $DB->query($DB->insert_string('exp_extensions',
      array(
            'extension_id' 	=> '',
            'class'        	=> "Auto_expire_entries",
            'method'       	=> "set_expiration_date",
            'hook'			=> "submit_new_entry_end",
            'settings'     	=> "",
            'priority'     	=> 10,
            'version'      	=> $this->version,
            'enabled'      	=> "y"
        	)
    	)
	);
}

// --------------------------------
//  Disable Extension
// --------------------------------

function disable_extension()
{
    global $DB;
    
    $DB->query("DELETE FROM exp_extensions WHERE class = 'Auto_expire_entries'");
    
    return true;
    
}


}
// END CLASS

?>