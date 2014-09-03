<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

/*
 * This class represent a model of Member and it extends Eloquent be a active record.
 */
class Member extends Eloquent implements UserInterface, RemindableInterface {

	/*
	 * table name
	 */
	protected $table = 'members';

	/*
	 * primary key
	 */
	protected $primaryKey = 'id';

	/**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier(){
        return $this->id;
    }

	/**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword(){
    	return '';
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail(){
        return $this->email;
    }
}
?>