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
	public static function hook( $class, $method, & $content )
    {
        $widget = $class;
        if( is_object( $widget ) )
        {
            $widget = get_class( $widget );
        }

        switch( $widget )
        {
            case 'Ayoola_Event_NewSession':
                switch( strtolower( $method ) )
                {
                    case '__construct':
                        if( ! $userInfo = self::authenticateSession() )
                        {
                        //    return false;
                        }   
                    break;
                }
            break;
            case 'Application_SiteInfo':
                switch( strtolower( $method ) )
                {
                    case 'view':
                        if( ! is_array( $content ) )
                        {
                            continue;
                        }
                        $settingsOptions = array(
                            'country_code',
                            'auth_options',
                            'android_download_link',
                            'ios_download_link',
                        );
                        foreach( $settingsOptions as $each )
                        {
                            $content[$each] = NativeApp_Settings::retrieve( $each );
                        }
                        $supported = NativeApp_Settings::retrieve( 'supported_versions' );
                        if( $supported = trim( $supported ) )
                        {
                            $supported = array_map( 'trim', explode( ',', $supported ) );
                            $content['supported_versions'] = $supported;
                        }
                        $content['current_stable_version'] = NativeApp_Settings::retrieve( 'current_stable_version' );
                    break;
                }
            break;
        }
    }



	// END OF CLASS
}
