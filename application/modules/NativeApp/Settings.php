<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_Table_Sample
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Settings.php Saturday 6th of June 2020 08:59AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class NativeApp_Settings extends PageCarton_Settings
{
	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		if( ! $settings = unserialize( @$values['settings'] ) )
		{
			if( is_array( $values['data'] ) )
			{
				$settings = $values['data'];
			}
			elseif( is_array( $values['settings'] ) )
			{
				$settings = $values['settings'];
			}
			else
			{
				$settings = $values;
			}
		}
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;

        $fieldset->addElement( array( 'name' => 'country_code', 'label' => 'Default Country Code', 'placeholder' => 'e.g. +234', 'value' => @$settings['country_code'], 'type' => 'InputText' ) );
        
		$fieldset->addElement( array( 'name' => 'sms_api', 'label' => 'SMS API Template', 'placeholder' => 'e.g. https://pmcsms.com/api/v1/http.php?api_key=45d0d9c6&recipient={{{phone_number}}}&message={{{message}}}&sender=senderid&route=3', 'value' => @$settings['sms_api'], 'type' => 'TextArea' ) );

		
		$fieldset->addLegend( 'NativeApp Settings' ); 
               
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
