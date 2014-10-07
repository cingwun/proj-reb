<?php

/*
 * this controller is used to handle all request of user modify
 */
class UsersModifyController extends \BaseController {

    /*
     * Display modify user page
     * params (int) $id
     */
    public function getModify() {
        try {
            $id = Sentry::getUser()->id;
            $user = Sentry::findUserById($id);

            $writeURL = URL::route('admin.user.write', array('id'=>$id,'where'=>Input::get('where', 'rebeauty')));

            $layout =  (Session::get('where')=='spa') ? "spa_admin._layouts.default" : "admin._layouts.default";
            
            return View::make('admin.users.view_modify', array(
                'user' => $user,
                'writeURL' => $writeURL,
                'layout' => $layout
            ));
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /*
     * write modify user
     */
    public function postWrite($id = null) {
        try {
            $password = Input::get('password');
            // Find the user using the user id
            $user = Sentry::findUserById($id);
            
            $user->last_name = Input::get('last_name');
            
            if(!empty($password))
                 $user->password = Input::get('password');

            $user->save();

            $views =  (Input::get('where')=='spa') ? "spa.admin.index" : "admin.index";

            return Redirect::route($views);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
?>