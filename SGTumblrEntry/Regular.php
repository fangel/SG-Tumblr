<?php

class SGTumblrEntryRegular extends SGTumblrEntry {
	public function __construct( SGTumblr $tumblr ) {
		parent::__construct( $tumblr );
		$this->data['type'] = 'regular';
	}
	
	public function fromJSON( $json ) {
		parent::fromJSON($json);
		$this->title( $json->{'regular-title'} );
		$this->body( $json->{'regular-body'} );
		return $this;
	}
	
	public function title( $title = null ) {
		if( $title ) $this->data['title'] = $title;
		return $this->data['title'];
	}
	
	public function body( $body = null ) {
		if( $body ) $this->data['body'] = $body;
		return $this->data['body'];
	}
}