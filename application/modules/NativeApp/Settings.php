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

        $fieldset->addElement( array( 'name' => 'country_code', 'label' => 'Default Country Code', 'placeholder' => 'e.g. 234', 'value' => @$settings['country_code'], 'type' => 'InputText' ) );
        
        $fieldset->addElement( array( 'name' => 'sms_api', 'label' => 'SMS API Template', 'placeholder' => 'e.g. https://pmcsms.com/api/v1/http.php?api_key=45d0d9c6&recipient={{{phone_number}}}&message={{{message}}}&sender=senderid&route=3', 'value' => @$settings['sms_api'], 'type' => 'TextArea' ) );
        
        $fieldset->addElement( array( 'name' => 'auth_options', 'label' => 'Authentication Options', 'value' => @$settings['auth_options'], 'type' => 'Checkbox' ), 
        array( 
                'phone_number_verification' => 'Verify Phone Number', 
                'email_verification' => 'Verify Email Addresses', 
            ) 
        );

        $fieldset->addElement( array( 'name' => 'supported_versions', 'label' => 'Supported Versions (Comma separated)', 'placeholder' => 'e.g. 0.0.1, 0.0.2, 0.0.3', 'value' => @$settings['supported_versions'], 'type' => 'InputText' ) );

        $fieldset->addElement( array( 'name' => 'current_stable_version', 'label' => 'Current Stable Version', 'placeholder' => 'e.g. 1.0.2', 'value' => @$settings['current_stable_version'], 'type' => 'InputText' ) );

        $fieldset->addElement( array( 'name' => 'android_download_link', 'label' => 'Android Store Link', 'placeholder' => 'e.g. https://play.google.com/store/apps/details?id=ng.com.example.app', 'value' => @$settings['android_download_link'], 'type' => 'InputText' ) );

        $fieldset->addElement( array( 'name' => 'ios_download_link', 'label' => 'iOS Store Link', 'placeholder' => 'e.g. https://apps.apple.com/ng/app/apple-store/id375380948', 'value' => @$settings['ios_download_link'], 'type' => 'InputText' ) );

		
		$fieldset->addLegend( 'NativeApp Settings' ); 
               
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
