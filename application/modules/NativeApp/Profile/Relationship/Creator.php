<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package   NativeApp_Profile_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see NativeApp_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton
 * @package   NativeApp_Profile_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class NativeApp_Profile_Relationship_Creator extends NativeApp_Profile_Relationship_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1, 98 );
	
    /**
     * The method does the whole Class Process
     * 
     */
	public function init()
    {
		try
		{ 
            $request = $_POST;
            if( empty( $request ) )
            { 
                $this->_objectData['badnews'] = 'No subject or object profile in request';
                return false;
            }
            if( empty( $request['subject'] ) OR ! $sInfo = self::getProfileInfo( $request['subject'] ) )
            { 
                $this->_objectData['badnews'] = 'Invalid subject profile';
                return false;
            }
            if( empty( $request['object'] ) OR ! $oInfo = self::getProfileInfo( $request['object'] ) )
            { 
                $this->_objectData['badnews'] = 'Invalid object profile';
                return false;
            }
            if( $request['object'] === $request['subject'] )
            { 
                $this->_objectData['badnews'] = 'Object and Subject profiles cannot be same';
                return false;
            }
            if(NativeApp_Profile_Relationship::getInstance()->select( null, array( 'subject' => $request['subject'], 'object' => $request['object'], ) ) )
            {
                $this->_objectData['badnews'] = 'Profiles already linked';
                return false;
            }

            if( $sInfo['username'] === Ayoola_Application::getUserInfo( 'username' ) )
            {
                $request['s_c'] = 1;
            }
            if( $oInfo['username'] === Ayoola_Application::getUserInfo( 'username' ) )
            {
                $request['o_c'] = 1;
            }
            if( $sInfo['username'] && $sInfo['username'] === $oInfo['username'] )
            {
                $request['s_c'] = 1;
                $request['o_c'] = 1;
            } 

            $response =NativeApp_Profile_Relationship::getInstance()->insert( $request );
            $this->_objectData['response'] = $response;
            $this->_objectData['goodnews'] = 'Profiles linked';
						
			//	Notify object
			$mailInfo['subject'] =  $oInfo['display_name'] . ' added to ' . $sInfo['display_name'];
            $mailInfo['body'] = 'The profile "' . $oInfo['display_name'] . '" has been added to ' . $sInfo['display_name'] . ' successfully.';
            
			$mailInfo['to'] =  $oInfo['email'];            
            self::sendMail( $mailInfo );
						
			//	Notify subject
			$mailInfo['subject'] =  $oInfo['display_name'] . ' added to ' . $sInfo['display_name'];
            $mailInfo['body'] = 'The profile "' . $oInfo['display_name'] . '" has been added to ' . $sInfo['display_name'] . ' successfully.';
            
			$mailInfo['to'] =  $sInfo['email'];            
            self::sendMail( $mailInfo );

		}
		catch(NativeApp_Profile_Exception $e )
		{ 
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
