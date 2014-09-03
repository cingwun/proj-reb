<?php
class ReservationsController extends \BaseController
{

    protected $filterExcept = array('store', 'create');

    public function __construct() {
        parent::permissionFilter();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        //
        return View::make('admin.reservations.index')->with('reservations', Reservation::paginate(5));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        //
        return View::make('aesthetics.reservations.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {

        //
        try {
            $reservation = new Reservation;
            $reservation->name = Input::get('name');
            $reservation->sex = Input::get('sex');
            $reservation->phone = Input::get('phone');
            $reservation->email = Input::get('email');
            $reservation->note = Input::get('note');

            $reservation->save();

            return View::make('aesthetics.reservations.ok');
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

        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {

        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {

        //

    }
}
