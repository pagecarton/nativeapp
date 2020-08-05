<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Profile_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Profile_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class NativeApp_Profile_Relationship_ShowAll extends NativeApp_Profile_Relationship_Abstract
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
            if( ! $userInfo = $this->authenticate() )
            {
                return $this->setUnauthorized();
            }
            if( isset( $_GET['subject'] ) )
            {
                $this->_dbWhereClause['subject'] = $_GET['subject'];                     
            }
            if( isset( $_GET['object'] ) )
            {
                $this->_dbWhereClause['object'] = $_GET['object'];                     
            }
            $this->_objectData = $this->getDbData(); 
        }
		catch( Application_Profile_Exception $e )
		{ 
			$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $this->getForm()->view(), true );
			return false; 
		}
    } 
	// END OF CLASS
}
