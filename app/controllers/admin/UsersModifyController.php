<?php

/*
 * this controller is used to handle all request of user modify
 */
class UsersModifyController extends \BaseController {

    /*
     * Display modify user page
     */
    public function getModify() {
        try {
            $user = Sentry::findUserById(Sentry::getUser()->id);

            $writeURL = URL::route('admin.user.write');
            return View::make('admin.users.view_modify',array(
                'user' => $user,
                'writeURL' => $writeURL
            ));
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /*
     * write modify user
     */
    public function postWrite() {
        try {
            $password = Input::get('password');
            // Find the user using the user id
            $user = Sentry::findUserById(Sentry::getUser()->id);
            
            $user->last_name = Input::get('last_name');
            
            if(!empty($password))
                 $user->password = Input::get('password');

            $user->save();

            return Redirect::route('admin.index');
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
?>