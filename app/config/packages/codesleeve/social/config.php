<?php
return array(
	/*
	 * facebook app
	 * created by Eric Huang(erichuang@talk2yam.com)
	 * created at 2014/04/14
	 */
	'facebook' => array(
		'key' => '435336489944403',
		'secret' => '42d960c3030deb46a6466efb8e7d45e9',
		'scopes' => array('email', 'user_birthday'),
		'redirect_url' => '/login/facebook',
	),

	'google' => array(
		'client_id' => '342760305446-v1vscuh92ppghvib3cn9a0ej767tf5bp.apps.googleusercontent.com',
		'client_secret' => 'eiWmKo0Y9jr-D_D3Ec15DtDf',
		'key' => '342760305446-v1vscuh92ppghvib3cn9a0ej767tf5bp.apps.googleusercontent.com',
		'secret' => 'eiWmKo0Y9jr-D_D3Ec15DtDf',
		'scopes' => array('openid', 'email'),
		'redirect_url' => '/login/google',
	)
);
?>