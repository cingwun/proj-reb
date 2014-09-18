<?php
class AuthController extends BaseController {

        /**
         *
         *
         */
        public function index(){
                return View::make('admin.index');
        }

        /**
         * Display the login page
         * @return View
         */
        public function getLogin()
        {
                
                if(Sentry::check()){
                        //return Redirect::route('admin.index');
                }
                
                
                return View::make('admin.auth.login', array('where'=>Input::get('where')));
        }
 
        /**
         * Login action
         * @return Redirect
         */
        public function postLogin()
        {
                $credentials = array(
                        'email' => Input::get('email'),
                        'password' => Input::get('password')
                );
 
                try
                {
                        $user = Sentry::authenticate($credentials, false);
 
                        if ($user && Input::get('where')=='rebeauty')
                        {
                                return Redirect::route('admin.index', array('where'=>Input::get('where')));
                        }
                        if ($user && Input::get('where')=='spa')
                        {
                                return Redirect::route('spa.admin.index', array('where'=>Input::get('where')));
                        }
                }
                catch(\Exception $e)
                {
                        return Redirect::route('admin.login')->withErrors(array('login' => $e->getMessage()));
                }
        }
 
        /**
         * Logout action
         * @return Redirect
         */
        public function getLogout()
        {
                Sentry::logout();
 
                return Redirect::route('admin.login');
        }
}
