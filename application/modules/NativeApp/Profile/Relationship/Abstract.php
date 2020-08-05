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
 * @seeNativeApp_Profile_Abstract
 */
 
require_once 'Application/Profile/Abstract.php';


/**
 * @category   PageCarton
 * @package   NativeApp_Profile_Editor
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class NativeApp_Profile_Relationship_Abstract extends NativeApp
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
    protected static $_accessLevel = array( 1, 98 );
    
    /**
     *
     * @var string
     */
	protected $_tableClass = 'NativeApp_Profile_Relationship';
	
}
