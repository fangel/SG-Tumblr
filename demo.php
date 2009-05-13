<?php

require 'SGTumblr.php';

$tumblr = new SGTumblr( 'http://fangel.tumblr.com', 'fangel@sevengoslings.net', 'ooh no I didn\'t' );

/*
$post = $tumblr->create('regular');
$post->title( 'API Test' );
$post->body( 'This is a test of SG-Tumblr' );
$post->date('10th january 2009 12:00pm');
$post->post();
*/

$posts = $tumblr->read();
foreach( $posts AS $p ) {
	var_dump($p->toArray());
}
