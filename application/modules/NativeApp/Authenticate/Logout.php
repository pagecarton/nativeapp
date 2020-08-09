<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    NativeApp_Authenticate_Logout
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Logout.php Sunday 10th of May 2020 08:41PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class NativeApp_Authenticate_Logout extends NativeApp_Authenticate
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Logout from App'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            self::populatePostData();
            $table = NativeApp_Authenticate_Table::getInstance();
            if( ! $userInfo = Ayoola_Application::getUserInfo() )
            {
                //    $this->_objectData['tokenize'] = $_SERVER['HTTP_AUTH_TOKEN'];
                return false;
            }
            //  $this->_objectData['userInfo'] = $userInfo;
            if( ! Ayoola_Application::getUserInfo( 'email' ) )
            {
                return false;
            }
            $response = $table->delete( 
                array(
                    'email' => Ayoola_Application::getUserInfo( 'email' ),
                )
             );
             $auth = new Ayoola_Access();
             $auth->logout();
             $this->_objectData['devices_logged_out'] = $response;
             $this->_objectData['current_device_token'] = $_SERVER['HTTP_AUTH_TOKEN'];
                 
            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}
	// END OF CLASS
}
