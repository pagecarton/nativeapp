<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package   NativeApp_Profile_Table
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Table.php Wednesday 27th of December 2017 10:46AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class NativeApp_Profile_Relationship extends PageCarton_Table_Protected
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.3';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'subject' => 'INPUTTEXT',
  'object' => 'INPUTTEXT',
  's_c' => 'INT',
  'o_c' => 'INT',
);


	// END OF CLASS
}
