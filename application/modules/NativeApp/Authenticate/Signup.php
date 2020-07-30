<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    NativeApp_Authenticate_Logout
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Logout.php Sunday 10th of May 2020 08:41PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class NativeApp_Authenticate_Signup extends NativeApp_Authenticate
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
	protected static $_objectTitle = 'Sign up for an account'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            self::populatePostData();
            $password = trim( $_POST['password'] );
            $email = trim( $_POST['email'] );
            $phone = trim( $_POST['phone_number'] );
            if( empty( $phone ) )
            {
                $this->_objectData['x'] = $_POST;
                $this->_objectData['badnews'] = "Phone number is required for sign up";
                return false;
            }
            elseif( ! is_numeric( $phone ) )
            {
                $this->_objectData['badnews'] = "Phone number should just contain numbers";
                return false;
            }
            elseif( empty( $email ) )
            {
                $this->_objectData['badnews'] = "Email address is required to signup";
                return false;
            }
            elseif( empty( $password ) )
            {
                $this->_objectData['badnews'] = "Password is required to signup";
                return false;
            }

            if( NativeApp_Settings::retrieve( "country_code" ) && $phone )
            {
                if( $phone[0] === '0' )
                {
                    $phone = NativeApp_Settings::retrieve( "country_code" ) . ltrim( $phone, '0' );
                }
                $phone = ltrim( $phone, '+' );
            }
            if( empty( $email ) )
            {
                $domain = Ayoola_Application::getDomainName();
                if( strpos( $domain, '.' ) === FALSE )
                {
                    $domain = $domain . '.com';
                } 
                $email = $phone . '@' . $domain;
            }
            $login = array(
                'email' => $email,
            );

            $signupParameters = $login + array(
                'phone_number' => $phone,
                'username' => '' . $phone,
                'password' => $password,
            );
            $signup = new Application_User_Creator( array( 'fake_values' => $signupParameters ) );
            $signup->view();
            if( $signup->getForm()->getBadnews() )
            {
                $badnews = $signup->getForm()->getBadnews();
                $this->_objectData['signup_details'] = $signupParameters;
                $this->_objectData['badnews'] = array_pop( $badnews );
                return false;
            }
            elseif( ! $userInfo = Application_User_Abstract::getUserInfo( $login ) )
            {

                $this->_objectData['badnews'] = "An unknown error occured, we can't log you in. Please contact support.";
                return false;
            }
            $authOptions = NativeApp_Settings::retrieve( "auth_options" ) ? : array();
            $this->_objectData['auth_options'] = $authOptions;
            if( in_array( 'phone_number_verification', $authOptions ) )
            {
                $response = self::sendSMSOTP( $phone );
                $this->_objectData['phone_number_verification'] = $response;
            }
            if( in_array( 'email_verification', $authOptions ) )
            {
                $response = self::sendEmailOTP( $email );
                $this->_objectData['email_verification'] = $response;
            }

            $authInfoToSave = NativeApp_Authenticate::getAuthInfo( $userInfo );
            
            $authInfo = array();
            $authInfo += $authInfoToSave;
            $authInfo += $userInfo;

            $this->_objectData['goodnews'] = "Sign up successful";
            $this->_objectData['auth_info'] = $authInfo;
            $this->_objectData['response'] = $response;
   

            // end of widget process
          
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
