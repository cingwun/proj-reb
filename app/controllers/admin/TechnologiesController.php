<?php
class TechnologiesController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return View::make('admin.technologies.index')->with('technologies', Technology::all()->sortBy('sort'));
    }
    // Index Technologies Showing
    public static function index_show() {
        $num_showed = 5;
         //Number of Technologies Showed
        try {
            $key = 'technologies_index';
            $data = false;//Cache::get($key);

            if (!$data) {
                $data = Technology::where('status', '=', 'Y')->take($num_showed)->orderBy('sort')->get();
                Cache::put($key, $data, 15);
            }
            return $data;
        }
        catch(Exception $e) {
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('admin.technologies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        try {
            $tech = new Technology;
            $tech->title = Input::get('title');
            $tech->image = Input::get('image_path');
            $tech->link = Input::get('link');
            $tech->target = Input::get('target');
            $tech->sort = Technology::max('sort') + 1;
            $tech->status = Input::get('status');

            $tech->save();

            return Redirect::route('admin.technologies.index');
        }
        catch(Exception $e) {

            return Redirect::back()->withInput()->withErrors('新增失敗');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        return View::make('admin.technologies.edit')->with('technology', Technology::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        try {
            $tech = Technology::find($id);
            $tech->title = Input::get('title');
            $tech->link = Input::get('link');
            $tech->target = Input::get('target');
            $tech->status = Input::get('status');

            if (Input::get('image_path') != NULL) $tech->image = Input::get('image_path');

            $tech->save();

            return Redirect::route('admin.technologies.index');
        }
        catch(Exception $e) {

            return Redirect::back()->withInput()->withErrors('修改失敗');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Technology::destroy($id);
    }

    public function sort() {

        $sort = explode(',', Input::get('sort'));

        if ($sort) {
            foreach ($sort as $key => $id) {
                $rank = Technology::find($id);
                $rank->sort = $key + 1;
                $rank->save();
            }
        }
    }
}
