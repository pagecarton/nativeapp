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

class NativeApp_Authenticate_OTP extends NativeApp_Authenticate
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
	protected static $_objectTitle = 'Login to App with OTP'; 

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
            $phone = trim( $_POST['phone_number'] );
            $email = trim( $_POST['email'] );
            $otpModeForPhone = true;
            if( empty( $phone ) || ! NativeApp_Settings::retrieve( "sms_api" ) )
            {
                if( empty( $email ) )
                {
                    $this->_objectData['badnews'] = "Phone number is required to login";
                    return false;
                }
                $otpModeForPhone = false;
            }
            elseif( ! is_numeric( $phone ) )
            {
                $this->_objectData['badnews'] = "Phone number should just contain numbers";
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

            if( ! $userInfo = Application_User_Abstract::getUserInfo( $login ) )
            {
                $signupParameters = $login + array(
                    'phone_number' => $phone,
                    'username' => '_' . $phone,
                    'password' => Ayoola_Form::hashElementName( $email . $phone ),
                );
                $signup = new Application_User_Creator( array( 'fake_values' => $signupParameters ) );
                $signup->view();
                if( ! $userInfo = Application_User_Abstract::getUserInfo( $login ) )
                {
                    if( $signup->getForm()->getBadnews() )
                    {
                        $badnews = $signup->getForm()->getBadnews();
                        $this->_objectData['signup_details'] = $signupParameters;
                        $this->_objectData['badnews'] = array_pop( $badnews );
                        return false;
                    }
                    $this->_objectData['badnews'] = "An unknown error occured, we can't log you in. Please contact support.";
                    return false;
                }
            }
            $authInfo = array();

            //  save auth info in data
            $authInfoToSave = NativeApp_Authenticate::getAuthInfo( $userInfo );

            $authInfo += $authInfoToSave;
            $authInfo += $userInfo;

            if( empty( $_POST['otp'] ) or ! $otp = $_POST['otp'] )
            {
                if( ! $signUpRequirements = NativeApp_Settings::retrieve( "auth_options" ) )
                {
                    $signUpRequirements = array();
                }
                if( ! empty( $phone ) && $otpModeForPhone )
                {
                    $response = self::sendSMSOTP( $phone );
                    if( ! in_array( 'phone_number_verification', $signUpRequirements ) )
                    {
                        $this->_objectData['goodnews'] = "Log in successful";
                        $this->_objectData['auth_info'] = $authInfo;
                        $this->_objectData['response'] = $response;
                    }
                }
                if( ! empty( $email ) )
                {
                    $response = self::sendEmailOTP( $email );
                    if( ! in_array( 'email_verification', $signUpRequirements ) )
                    {
                        $this->_objectData['goodnews'] = "Log in successful";
                        $this->_objectData['auth_info'] = $authInfo;
                        $this->_objectData['response'] = $response;
                    }
                }
                $this->_objectData += $response;

                if( $signUpRequirements = NativeApp_Settings::retrieve( "auth_options" ) )
                {

                }
                return true;
            }
            if( ! $otpInfo = NativeApp_Authenticate_OTPTable::getInstance()->selectOne( null, $where + array( 'otp' => $otp ) ) )
            {
                $this->_objectData['x'] = $_POST;
                $this->_objectData['badnews'] = "Wrong OTP";
                return false;
            }
            NativeApp_Authenticate_OTPTable::getInstance()->delete( $where );

            $this->_objectData['goodnews'] = "Log in successful";
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
    
    /**
     * 
     * 
     */
	public static function sendSMSOTP( $phone )
    {    
        $otp = rand( 100000, 999999 );
        $message = 'Your ' . Ayoola_Application::getDomainName() . ' OTP is ' . implode( '-', str_split( $otp, 3 ) );
        $search = array( '{{{phone_number}}}', '{{{message}}}' );
        $message = urlencode( $message );
        $replace = array( $phone, $message  );
        $where = array( 'phone_number' => $phone );
        NativeApp_Authenticate_OTPTable::getInstance()->delete( $where );
        $responseT = NativeApp_Authenticate_OTPTable::getInstance()->insert( $where + array( 'otp' => $otp ) );
        $x = array();
        if( empty( $phone ) )
        {
            $x['badnews'] = "No phone number is set";
            return $x;
        }
        if( ! $smsApi = NativeApp_Settings::retrieve( "sms_api" ) )
        {
            $x['badnews'] = "No SMS API Template is set on server. Please check the NativeApp Settings";
            return $x;
        }
        $smsApi = trim( str_ireplace( $search, $replace, $smsApi ) );
        if( stripos( $smsApi, 'https://' ) !== 0 && stripos( $smsApi, 'http://' ) !== 0 )
        {
            $x['badnews'] = "SMS API template must start with 'https://' e.g. https://pmcsms.com/api/v1/http.php?api_key=362g59483&recipient={{{phone_number}}}&message={{{message}}}&sender=senderid&route=3";
            return $x;
        }
        $response = self::fetchLink( $smsApi );
        $x['sms_response'] = $response;
        $x['otp'] = $otp;
        $x['goodnews'] = 'OTP Sent to ' . $phone;
        return $x;
    }    
    
    /**
     * 
     * 
     */
	public function sendEmailOTP( $email )
    {    
        $x = array();
        $otp = rand( 100000, 999999 );
        $message = 'Your ' . Ayoola_Application::getDomainName() . ' OTP: ' . implode( '-', str_split( $otp, 3 ) );
        $search = array( '{{{phone_number}}}', '{{{message}}}' );
        $replace = array( $phone, $message  );
        $where = array( 'email' => $email );
        NativeApp_Authenticate_OTPTable::getInstance()->delete( $where );
        $responseT = NativeApp_Authenticate_OTPTable::getInstance()->insert( $where + array( 'otp' => $otp ) );

        $mailInfo = array(
            'to' => $email,
            'subject' => 'OTP',
            'body' => $message,
        );

        $response = self::sendMail( $mailInfo );
        $x['email_response'] = $response;
        $x['otp'] = $otp;
        $x['goodnews'] = 'OTP Sent to ' . $email;
        return $x;
	}

	// END OF CLASS
}
