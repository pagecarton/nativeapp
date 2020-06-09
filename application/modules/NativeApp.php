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
     * 
     */
	public function init()
    {    
        $this->setViewContent( self::__( '<p class="goodnews">Native app running successfully.</p>' ) ); 
    }
	// END OF CLASS
}
