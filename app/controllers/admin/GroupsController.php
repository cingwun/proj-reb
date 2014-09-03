<?php

class GroupsController extends \BaseController {

        public function __construct(){

                $this->beforeFilter(function(){
                        if(!Sentry::getUser()->hasAccess(array('system')))
                        {
                                return Redirect::route('admin.index');
                        }
                });

        }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
                return View::make('admin.groups.index')->with('groups',Sentry::findAllGroups());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
                return View::make('admin.groups.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
                try
		{
    			// Create the group
    			$group = Sentry::createGroup(array(
        			'name'        => Input::get('name'),
        			'permissions' => (is_array(Input::get('permissions')))?array_fill_keys(Input::get('permissions'),1):array(),
    			));
                        return Redirect::route('admin.groups.index');
		}
		catch (Cartalyst\Sentry\Groups\NameRequiredException $e)
		{
                        return Redirect::back()->withInput()->withErrors('Name field is required');
		}
		catch (Cartalyst\Sentry\Groups\GroupExistsException $e)
		{
                        return Redirect::back()->withInput()->withErrors('Group already exists');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
                return View::make('admin.groups.edit')->with('group',Sentry::findGroupById($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
                try
		{
    			// Find the group using the group id
    			$group = Sentry::findGroupById($id);

    			// Update the group details
    			$group->name = Input::get('name');
                        
                        $newPermissions = (is_array(Input::get('permissions')))?array_fill_keys(Input::get('permissions'),1):array();
                        $deletePermissions = array_map(function($val) { return 0; }, array_diff_key( $group->permissions , $newPermissions));

                        
    			$group->permissions = array_merge($newPermissions,$deletePermissions);

    			// Update the group
    			if ($group->save())
    			{
        			// Group information was updated
                                return Redirect::route('admin.groups.index');
    			}
    			else
    			{
        			// Group information was not updated
                                return Redirect::back()->withInput()->withErrors('update error');
                                
    			}
		}
		catch (Cartalyst\Sentry\Groups\GroupExistsException $e)
		{
                        return Redirect::back()->withInput()->withErrors('Group already exists.');
		}
		catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
                        return Redirect::back()->withInput()->withErrors('Group was not found.');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
                try
		{
    			// Find the group using the group id
    			$group = Sentry::findGroupById($id);

    			// Delete the group
    			$group->delete();
		}
		catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
    			echo 'Group was not found.';
		}
	}

}
