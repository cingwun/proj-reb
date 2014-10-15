<?php
class TechnologiesController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $techsLang = array(
            'tw' => array(),
            'cn' => array()
        );

        $techTWCmd = Technology::where('lang', 'tw')
                               ->orderBy('sort')
                               ->get();
        $techCNCmd = Technology::where('lang', 'cn')
                               ->orderBy('sort')
                               ->get();
        if($techTWCmd && $techCNCmd)
            $techsLang = array(
                'tw' => array(
                    'data' => $techTWCmd,
                    'title' => '繁體列表'
                ),
                'cn' => array(
                    'data' => $techCNCmd,
                    'title' => '簡體列表'
                )
            );

        return View::make('admin.technologies.index', array(
            'techsLang' => $techsLang
        ));
    }

    // Index Technologies Showing
    public static function index_show() {
        $num_showed = 5;

        //Number of Technologies Showed
        try {
            $key = 'technologies_index';
            $data = Cache::get($key);
            if (!$data) {
                $data = Technology::where('status', '=', 'Y')
                                  ->take($num_showed)
                                  ->orderBy('sort')
                                  ->get();
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
            $image = Input::get('image');

            //create tw
            $techTW = new Technology;
            $techTW->title = Input::get('title');
            $techTW->image = Input::get('image_path');
            $techTW->link = Input::get('link');
            $techTW->target = Input::get('target');
            $techTW->sort = Technology::max('sort') + 1;
            $techTW->status = Input::get('status');
            $techTW->image = $image[0];
            $techTW->lang = 'tw';

            $techTW->save();

            $insertTWId = $techTW->id;

            //create cn
            $techCN = new Technology;
            $techCN->title = Input::get('title');
            $techCN->image = Input::get('image_path');
            $techCN->link = Input::get('link');
            $techCN->target = Input::get('target');
            $techCN->sort = Technology::max('sort') + 1;
            $techCN->status = Input::get('status');
            $techCN->image = $image[0];
            $techCN->lang = 'cn';
            $techCN->ref = $insertTWId;

            $techCN->save();

            $insertCNId = $techCN->id;

            //set tw ref
            $techCmd = Technology::find($insertTWId);
            $techCmd->ref = $insertCNId;
            $techCmd->save();

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
    public function edit($id = null) {
        $tech = Technology::find($id);
        $techImage = array();
        $techImage[] = array(
            'id' => $tech->id,
            'image' => $tech->image,
            'text' => ""
        );
        return View::make('admin.technologies.edit', array(
            'technology' => $tech,
            'techImage' => $techImage
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        try {
            $image = Input::get('image');

            $tech = Technology::find($id);
            $tech->title = Input::get('title');
            $tech->link = Input::get('link');
            $tech->target = Input::get('target');
            $tech->status = Input::get('status');
            $tech->image = $image[0];
            
            $tech->save();

            return Redirect::route('admin.technologies.index');
        }
        catch(Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        try {
            $techCmd = Technology::find($id);
            $techCmd->delete();
            Technology::destroy($techCmd->ref);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
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
