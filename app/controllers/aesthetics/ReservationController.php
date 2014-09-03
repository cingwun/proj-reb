<?php
namespace aesthetics;

/*
 * This controller is used to handle request of Services
 */
class ReservationController extends \BaseController {

    /**
     * this method is used to handle AJAX request for form submit
     */
    public function postForm(){
        try{
            if (!isset($_POST))
                throw new Exception('Error request [10]');

            $name = \Arr::get($_POST, 'name', false);
            $sex = \Arr::get($_POST, 'sex', 'female');
            $phone = \Arr::get($_POST, 'phone', false);
            $email = \Arr::get($_POST, 'email', false);
            $note = \Arr::get($_POST, 'note', false);

            if (empty($name))
                throw new Exception('Error request [1100]');

            if (empty($phone))
                throw new Exception('Error request [1101]');

            if (empty($email))
                throw new Exception('Error request [1110]');
            else{
                $reg = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';
                if (preg_match($reg, $email)==false)
                    throw new Exception('Error request [11101]');
            }

            if (empty($note))
                throw new Exception('Error request [1101]');

            $model = new \Reservation;
            $model->name = $name;
            $model->sex = $sex;
            $model->phone = $phone;
            $model->email = $email;
            $model->note = $note;

            if (!$model->save())
                throw new Exception("Error request [110]");

            return \Response::json(array(
                'status' => 'ok'
            ));
        }catch(Exception $e){
            return \Response::json(array(
                'status' => 'error',
                'message' => $e->getMessage()
            ));
        }
    }

}
