<?php

class PermissionsController extends \BaseController {

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
                if((Session::get('where'))=='rebeauty')
                	return View::make('admin.permissions.index')->with('permissions',Permission::all()->sortBy('sort'));
                else
                	return View::make('spa_admin.permissions.index')->with('permissions',Permission::all()->sortBy('sort'));

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
                if((Session::get('where'))=='rebeauty')
                	return View::make('admin.permissions.create')->with('permissions',Permission::all()->sortBy('sort'));
                else
                	return View::make('spa_admin.permissions.create')->with('permissions',Permission::all()->sortBy('sort'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
                try{
                        $permission = new Permission;
                        $permission->name = Input::get('name');
                        $permission->title = Input::get('title');
                        $permission->status = Input::get('status');
                        $permission->sort = Permission::max('sort')+1;

                        $permission->save();

                        return Redirect::route('admin.permissions.index');
                }catch (Exception $e) {
                        return Redirect::back()->withInput()->withErrors('新增失敗');
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
                if((Session::get('where'))=='rebeauty')
                	return View::make('spa_admin.permissions.edit')->with('permission',Permission::find($id));
                else
                	return View::make('spa_admin.permissions.edit')->with('permission',Permission::find($id));
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
                try{
                        $permission = Permission::find($id);

                        $permission->title = Input::get('title');
                        $permission->status = Input::get('status');

                        $permission->save();

                        return Redirect::route('admin.permissions.index');
                }catch (Exception $e){

                        return Redirect::back()->withInput()->withErrors('修改失敗');

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
                try{
                	$permission = Permission::find($id);

                	$groups = Sentry::findAllGroups();

                	foreach($groups as $group){
                		if($group->hasAccess(array($permission->name))){
                                	$group->permissions = array("$permission->name"=>0);
                                	$group->save();
                        	}	
                	}
			Permission::destroy($id);
		}catch(Exception $e){
			
                }
	}

        public function sort(){
                $sort = explode(',',Input::get('sort'));

                if($sort){
                        foreach($sort as $key=>$id){
                                $permission = Permission::find($id);
                                $permission->sort = $key+1;
                                $permission->save();
                        }
                }

        }

}
