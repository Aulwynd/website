<?php
namespace Destiny\Twitter;

use Destiny\Common\Authentication\AuthenticationService;
use Destiny\Common\Exception;
use Destiny\Common\Authentication\AuthenticationRedirectionFilter;
use Destiny\Common\Authentication\AuthenticationCredentials;
use Destiny\Common\Config;
use Destiny\Common\User\UserService;
use Destiny\Common\Session;

class TwitterAuthHandler {
	
	/**
	 * The current auth type
	 *
	 * @var string
	 */
	protected $authProvider = 'twitter';
	
	/**
	 *
	 * @param array $params        	
	 * @throws Exception
	 */
	public function execute(array $params) {
		$UserService = UserService::instance ();
		$authService = AuthenticationService::instance ();
		if ((! isset ( $params ['oauth_token'] ) || empty ( $params ['oauth_token'] )) || ! isset ( $params ['oauth_verifier'] ) || empty ( $params ['oauth_verifier'] )) {
			throw new Exception ( 'Authentication failed' );
		}
		$oauth = Session::set ( 'oauth' );
		if ($params ['oauth_token'] !== $oauth ['oauth_token']) {
			throw new Exception ( 'Invalid login session' );
		}
		$twitterOAuthConf = Config::$a ['oauth'] ['providers'] ['twitter'];
		$tmhOAuth = new \tmhOAuth ( array (
				'consumer_key' => $twitterOAuthConf ['clientId'],
				'consumer_secret' => $twitterOAuthConf ['clientSecret'],
				'token' => $oauth ['oauth_token'],
				'secret' => $oauth ['oauth_token_secret'],
				'curl_connecttimeout' => Config::$a ['curl'] ['connecttimeout'],
				'curl_timeout' => Config::$a ['curl'] ['timeout'],
				'curl_ssl_verifypeer' => Config::$a ['curl'] ['verifypeer'] 
		) );
		$code = $tmhOAuth->user_request ( array (
				'method' => 'POST',
				'url' => $tmhOAuth->url ( 'oauth/access_token', '' ),
				'params' => array (
						'oauth_verifier' => trim ( $params ['oauth_verifier'] ) 
				) 
		) );
		if ($code != 200) {
			throw new Exception ( 'Failed to retrieve user data' );
		}
		$data = $tmhOAuth->extract_params ( $tmhOAuth->response ['response'] );
		$authCreds = $this->getAuthCredentials ( $oauth ['oauth_token'], $data );
		$authCredHandler = new AuthenticationRedirectionFilter ();
		return $authCredHandler->execute ( $authCreds );
	}
	
	/**
	 * Build a standard auth array from custom data array from api response
	 *
	 * @param string $code        	
	 * @param array $data        	
	 * @return AuthenticationCredentials
	 */
	private function getAuthCredentials($code, array $data) {
		if (empty ( $data ) || ! isset ( $data ['user_id'] ) || empty ( $data ['user_id'] )) {
			throw new Exception ( 'Authorization failed, invalid user data' );
		}
		$arr = array ();
		$arr ['authProvider'] = $this->authProvider;
		$arr ['authCode'] = $code;
		$arr ['authId'] = $data ['user_id'];
		$arr ['authDetail'] = $data ['screen_name'];
		$arr ['username'] = $data ['screen_name'];
		$arr ['email'] = '';
		return new AuthenticationCredentials ( $arr );
	}
}