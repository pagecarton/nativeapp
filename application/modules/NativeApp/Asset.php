<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    NativeApp_Asset
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Asset.php Sunday 10th of May 2020 10:00AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class NativeApp_Asset extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.0';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'remote_url' => 'INPUTTEXT',
  'local_url' => 'INPUTTEXT',
);


	// END OF CLASS
}
