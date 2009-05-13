<?php

require './SGTumblrException.php';
require './SGTumblrEntry/Entry.php';
require './SGTumblrEntry/Regular.php';

class SGTumblr {
	const GENERATOR = 'SG-Tumblr';
	private $url = '';
	private $email = null;
	private $password = null;
	
	public function __construct( $url, $email = null, $password = null ) {
		$this->url = $url;
		$this->email = $email;
		$this->password = $password;
	}
	
	public function url( $url = false ) {
		if( $url ) $this->url = $url;
		return $this->url;
	}
	
	public function email( $email = false ) {
		if( $email ) $this->email = $email;
		return $this->email;
	}
	
	public function password( $password = false ) {
		if( $password ) $this->password = $password;
		return $this->password;
	}
	
	public function read() {
		$c = curl_init($this->url . '/api/read/json');
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($c);
		$status = curl_getinfo($c, CURLINFO_HTTP_CODE);
		curl_close($c);
		
		if( $status == 200 ) {
			$json = substr($result, 22, -2);
			$_result = json_decode( $json );
			
			$posts = array();
			foreach( $_result->posts AS $_p ) {
				$posts[] = $this->create( $_p->type )->fromJSON( $_p );
			}
			
			return $posts;
		} else {
			throw new SGTumblrException('Unable to read Tumblr data');
		}
	}
	
	public function create( $type = 'regular' ) {
		switch( $type ) {
			case 'regular':
			default:
				return new SGTumblrEntryRegular( $this );
		}
	}
	
	public function edit( $id ) {
	
	}
}