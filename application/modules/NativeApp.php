<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    NativeApp
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: NativeApp.php Sunday 10th of May 2020 09:57AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class NativeApp extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * Response mode 
     *
     * @var string
     */
	protected $_playMode = self::PLAY_MODE_JSON;
	
    /**
     * Supported client versions. 
     * Clients versions not in this list will be asked to update immediately
     *
     * @var array
     */
	protected static $_supportedClientVersions = array( 
        '0.1.0',
        '0.1.1',
        '0.1.2',
     );
	
    /**
     * Current Stable Version
     * Changing this version will prompt clients with older version to update
     *
     * @var string
     */
	protected static $_currentStableClientVersion = '0.1.2';
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Native App Integration Module'; 

    /**
     * 
     * 
     */
	public static function populatePostData()
    {    
        if( empty( $_POST ) )
        {
            //  Sometimes, JSON don't get passed
            $_POST = json_decode( file_get_contents( 'php://input' ), true );
        }
    }
    
    /**
     * 
     */
	public function setUnauthorized()
    {
        header("HTTP/1.1 401 Unauthorized");
        $this->_objectData['badnews'] = 'Current user is unauthorized';
        $this->_objectData['http_code'] = 401;
        return false;
    }

    /**
     * Returns user info from auth token
     * 
     * @param array auth info
     * @return mixed Array of auth user info
     * 
     */
	public static function authenticateSession( $authInfo = array() )
    {
        if( empty( $authInfo ) )
        {
            self::populatePostData();

            $authInfo = array();
            if( ! empty( $_SERVER['HTTP_AUTH_TOKEN'] ) && ! empty( $_SERVER['HTTP_AUTH_USER_ID'] ) )
            {
                $authInfo['auth_token'] = $_SERVER['HTTP_AUTH_TOKEN'];
                $authInfo['auth_user_id'] = $_SERVER['HTTP_AUTH_USER_ID'];
            }
            elseif( ! empty( $_REQUEST['auth_token'] ) )
            {
                $authInfo['auth_token'] = $_REQUEST['auth_token'];
                $authInfo['auth_user_id'] = $_REQUEST['auth_user_id'] ? : $_REQUEST['user_id'];
            }
            else
            {
                return false;
            }
        }
        if( $userInfo = NativeApp_Authenticate::getAuthUserInfo( $authInfo ) )
        {
            if( $userInfo = Ayoola_Access_Login::localLogin( $x ) )
            {
                
            }
            return $x;
        }
        return false;
    }

    /**
     * Returns user info from auth token
     * 
     * @param array auth info
     * @return mixed Array of auth user info
     * 
     */
	public function authenticate( $authInfo = array() )
    {
        if( $x = self::authenticateSession( $authInfo ) )
        {
            $this->_objectData['authenticated'] = true;
            return $x;
        }
        $this->_objectData['authenticated'] = false;
        return false;
    }

    /**
     * 
     * 
     */
	public function init()
    {    
        $this->setViewContent( self::__( '<p class="goodnews">Native app running successfully.</p>' ) ); 
    }
	// END OF CLASS
}
