<?php

class BackendSwitchController extends BaseController
{
	public function getRebeauty() {
		Session::put('where', 'rebeauty');
		return Redirect::route('admin.index');
	}

	public function getSpa() {
		Session::put('where', 'spa');
		return \View::make('spa_admin._layouts.default');
	}

}