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

class NativeApp_Authenticate extends NativeApp
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
	protected static $_objectTitle = 'Authenticate Client'; 

    /**
     * Returns user info from auth token
     * 
     * @param array Auth Info
     * @return mixed
     * 
     */
	public static function getAuthUserInfo( array $authInfo )
    {
        $table = NativeApp_Authenticate_Table::getInstance();

        $userIdentifier = array( 
            'user_id' => $authInfo['auth_user_id'] ? : $authInfo['user_id'],
            'auth_token' => $authInfo['auth_token'],
        );
        if( ! $auth = $table->selectOne( null, $userIdentifier ) )
        {
            return false;
        }
        $userInfo = Application_User_Abstract::getUserInfo( array( 'email' => $auth['email'] ) );
        return $userInfo;
    }

    /**
     * Returns user info from auth token
     * 
     * @param array Auth Info
     * @return array
     * 
     */
	public static function getAuthInfo( array $userInfo )
    {
        $response = array();
        $authToken = md5( uniqid( json_encode( $userInfo ), true ) );

        //  save auth info in data
        $table = NativeApp_Authenticate_Table::getInstance();

        $authInfoToSave = array( 
            'user_id' => strval( $userInfo['user_id'] ),
            'email' => strtolower( $userInfo['email'] ),
            'auth_token' => $authToken,
            'device_info' => $_POST['device_info'],
            'auth_data' => $userInfo['auth_data'],
        );

        $table->insert( $authInfoToSave );

        $response += $authInfoToSave;
        $response += $userInfo;
        $response += self::getVersionInfo();
        return $response;
    }

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
            if( empty( $_POST['email'] ) || empty( $_POST['password'] ) )
            {

                //  error
                $errorInfo = array(
                    'badnews' => 'email & password cannot be empty'
                );
                $this->_objectData = $errorInfo;
                return false; 
            }
            $authInfo = array( 
                'email' => $_POST['email'],
                'password' => $_POST['password'],
            );
           
            if( $userInfo = Ayoola_Access_Login::localLogin( $authInfo ) )
            {
                $this->_objectData += self::getAuthInfo( $userInfo );
            }
            else
            {
                //  error
                $errorInfo = array(
                    'badnews' => 'Invalid email or password. You can create a new account or reset your password on ' . Ayoola_Page::getDefaultDomain()
                );
                $this->_objectData = $errorInfo;
            }
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            //    $this->setViewContent( self::__( '<p class="badnews">' . $e->getMessage() . '</p>' ) ); 
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}
	// END OF CLASS
}
