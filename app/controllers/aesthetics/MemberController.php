<?php
namespace aesthetics;

/*
 * This controller is used to handle request of Member
 */
class MemberController extends \BaseController {

	/*
	 * dispaly get social login
	 */
	public function getLogin($social){
		try{
			switch($social){
				case 'facebook':
					$su = \Social::facebook('user');
					break;
				case 'google':
					$su = \Social::google('user');
					break;
				default:
					$su = null;
			}

			if ($su===null)
				return \Redirect::route('frontend.index');

			$m = \Member::where('uid', '=', $su->id)->where('social', '=', $social)->first();

			if ($m==null){
				$m = new \Member;
				$m->uid = $su->id;
				$m->social = $social;
				$m->name = $su->name;
				$m->email = $su->email;

				if ($social=='facebook'){
					if (isset($su->birthday))
						$m->birthday = date('Y-m-d', strtotime($su->birthday));
					if (isset($su->gender))
						$m->gender = substr($su->gender, 0, 1);
				}
				$m->save();
			}

			// register user into Auth that is a global variable
			\Auth::login($m);
			return \Redirect::route('frontend.index');
		}catch(Exception $e){
			echo $e->getMessage();
			exit;
		}
	}

	/*
	 * handle post request of login
	 */
	public function postAjaxLogin(){
		try{
			if (!isset($_POST))
				throw new Exception('Request error');

			$id = \Input::get('id', false);
			$passwd = \Input::get('password', false);

			if (!$id || !$password)
				throw new Exception('Parameter error');

			$m = \Member::where('uid', '=', md5($id))->where('social', '=', 'rebeauty')->get();

			if ($m==null)
				throw new Exception('Not founded');

			if (!\Hash::check($passwd, $m->password))
				throw new Exception('帳號或密碼錯誤');

			// register user into Auth that is a global variable
			\Auth::login($m);
			return \Redirect::route('frontend.index');
		}catch(Exception $e){
			return Response::json(array('status'=>'error', 'message'=>$e->getMessage(), '_token'=>csrf_token()));
		}
	}
}
?>
