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

class NativeApp_ProcessWidgetForm extends NativeApp
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
	protected static $_objectTitle = 'Process Widget Form'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            if( empty( $_POST['widget_class'] ) )
            {
                $this->_objectData['badnews'] = "Widget class not set";
                return false; 
            }
            $class = $_POST['widget_class'];

            if( ! Ayoola_Loader::loadClass( $class ) )
            {
                $this->_objectData['badnews'] = "Invalid Widget Class \"" . $class . '"';
                return false; 
            }
            $parameters =  array( 
                'fake_values' => $_POST, 
                'return_json' => true,
                'play_mode' => $this->getParameter( 'play_mode' ) 
            );
            $obj = new $class( $parameters );
            $response = $obj->view();
            $this->_objectData['form_data_sent'] = $_POST;
            $this->_objectData['response'] = $response;
            $this->_objectData['widget_options'] = $obj->getForm()->getNames();
            if( $badnews = $obj->getForm()->getBadnews() )
            {
                $this->_objectData['badnews'] = array_pop( $badnews );
                return false;
            }
            elseif( $values = $obj->getForm()->getValues()  )
            {
                $this->_objectData['processed_form_values'] = $values;
            }


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
