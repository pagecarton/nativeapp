<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    NativeApp_Authenticate
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Authenticate.php Monday 23rd of March 2020 09:36AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class NativeApp_Hook extends NativeApp
{
    
    
    /**
     * Converts youtube links in output to embedded video
     * 
     */
	public function hook( $class, $method, & $content )
    {
        switch( strtolower( $method ) )
        {
            case '__construct':
                if( ! $userInfo = self::authenticateSession() )
                {
                //    return false;
                }   
            break;
        }
    }



	// END OF CLASS
}
