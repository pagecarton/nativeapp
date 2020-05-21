<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    NativeApp_Authenticate_Table
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Monday 23rd of March 2020 09:49AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class NativeApp_Authenticate_Table extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.1';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'user_id' => 'INPUTTEXT',
  'auth_token' => 'INPUTTEXT',
  'email' => 'INPUTTEXT',
  'device_info' => 'INPUTTEXT',
);


	// END OF CLASS
}
