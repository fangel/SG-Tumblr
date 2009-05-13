<?php

abstract class SGTumblrEntry {
	protected $tumblr = null;
	protected $id;
	protected $url;
	protected $data;
		
	public function __construct( SGTumblr $tumblr ) {
		$this->tumblr = $tumblr;
		$this->data['generator'] = SGTumblr::GENERATOR;
		$this->data['format'] = 'html';
		$this->data['private'] = false;
	}
	
	public function fromJSON( $json ) {
		$this->id = $json->id;
		$this->url = $json->url;
		$this->date( $json->date );
		$this->format( $json->format );
	}
	
	public function id() {
		return $this->id;
	}
	
	public function url() {
		return $this->url;
	}
	
	public function date( $new = null ) {
		if( $new && ($_new = strtotime($new)) !== false ) $this->data['date'] = date('Y-m-d H:i:s', $_new);
		return $this->data['date'];
	}
	
	public function isPrivate( $private = null ) {
		if( $public !== null ) $this->data['private'] = (bool) $private;
		return $this->data['private'];
	}
	
	public function tags( $tags = null ) {
		if( $tags ) $this->data['tags'] = $tags;
		return $this->data['tags'];
	}
	
	public function format( $format = null ) {
		if( $format && in_array( $format, array('html','markdown')) ) $this->data['format'] = $format;
		return $this->data['format'];
	}
	
	public function group( $group = null ) {
		if( $group ) $this->data['group'] = $group;
		return $this->data['group'];
	}
	
	public function toArray() {
		$arr = array();
		if( $this->id() ) $arr['id'] = $this->id();
		if( $this->url() ) $arr['url'] = $this->url();
		$arr += array_filter($this->data, 'strlen');
		return $arr;
	}
	
	public function post() {
		$data = array_filter($this->data, 'strlen');
		$data['email'] = $this->tumblr->email();
		$data['password'] = $this->tumblr->password();
		if( $this->id() ) $data['id'] = $this->id();
		
		$c = curl_init('http://www.tumblr.com/api/write');
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query( $data ) );
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($c);
		$status = curl_getinfo($c, CURLINFO_HTTP_CODE);
		curl_close($c);

		if ($status == 201) {
			$this->id = (int) $result;
			return true;
		} else if ($status == 403) {
			throw new SGTumblrException('Bad email or password');
		} else {
			throw new SGTumblrException('Error: ' . $result);
		}
	}
}