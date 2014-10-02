<?php
class UsersController extends \BaseController
{

    public function __construct() {
        $this->beforeFilter(function () {
            if (!Sentry::getUser()->hasAccess(array('system'))) {
                return Redirect::route('admin.index');
            }
        });
    }

    /**
     * displaly dashboard
     */
    public function index() {
        $where = Input::get('where', 'rebeauty');
        $view = (($where=='rebeauty')) ? 'admin.users.view_index' : 'spa_admin.users.index';
        return View::make($view, array('users'=>Sentry::findAllUsers(), 'where'));
    }

    public function show($id) {
    }

    public function create() {
        if ((Session::get('where')=='rebeauty')) return View::make('admin.users.create')->with('users', Sentry::findAllUsers());
        else return View::make('spa_admin.users.create')->with('users', Sentry::findAllUsers());
    }

    public function store() {
        try {
            // Create the user
            $user = Sentry::createUser(array('email' => Input::get('email'), 'password' => Input::get('password'), 'last_name' => Input::get('last_name'), 'activated' => true,));

            if (Input::get('group')) {
                $group = Sentry::findGroupById(Input::get('group'));

                // Assign the group to the user
                $user->addGroup($group);
            }

            return Redirect::route('admin.users.index');
        }
        catch(Cartalyst\Sentry\Users\LoginRequiredException $e) {

            //echo 'Login field is required.';
            return Redirect::back()->withInput()->withErrors('Login field is required.');
        }
        catch(Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            return Redirect::back()->withInput()->withErrors('Password field is required.');
        }
        catch(Cartalyst\Sentry\Users\UserExistsException $e) {
            return Redirect::back()->withInput()->withErrors('User with this login already exists.');
        }
        catch(Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
            return Redirect::back()->withInput()->withErrors('Group was not found.');
        }
    }

    public function edit($id) {
        $user = Sentry::findUserById($id);

        return View::make('admin.users.edit')->with('user', Sentry::findUserById($id));
    }

    public function update($id) {
        try {
            $password = Input::get('password');
            // Find the user using the user id
            $user = Sentry::findUserById($id);
            
            $user->last_name = Input::get('last_name');

            $user->activated = Input::get('activated');
            
            if(!empty($password))
                 $user->password = Input::get('password');

            // Update the user
            if ($user->save()) {

                $userGroups = $user->groups()->lists('group_id');

                $selectedGroups = array(Input::get('group'));

                $groupsToAdd = array_diff($selectedGroups, $userGroups);
                $groupsToRemove = array_diff($userGroups, $selectedGroups);

                foreach ($groupsToAdd as $groupId) {
                    $group = Sentry::findGroupById($groupId);

                    $user->addGroup($group);
                }

                foreach ($groupsToRemove as $groupId) {
                    $group = Sentry::findGroupById($groupId);

                    $user->removeGroup($group);
                }

                // User information was updated

            } else {

                // User information was not updated

            }
            return Redirect::route('admin.users.index');
        }
        catch(Cartalyst\Sentry\Users\UserExistsException $e) {

            return Redirect::back()->withInput()->withErrors('User with this login already exists.');
        }
        catch(Cartalyst\Sentry\Users\UserNotFoundException $e) {

            return Redirect::back()->withInput()->withErrors('User was not found.');
        }
    }

    public function destroy($id) {
        try {

            // Find the user using the user id
            $user = Sentry::findUserById($id);

            // Delete the user
            $user->delete();
        }
        catch(Cartalyst\Sentry\Users\UserNotFoundException $e) {
            echo 'User was not found.';
        }
    }
}
