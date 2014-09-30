<?php
class BaseController extends Controller
{

    /**
     * @var locale
     */
    private $locale;

    /**
     * construct
     */
    public function __construct(){
        $this->locale = App::getLocale();
    }

    /**
     * get locale
     * @return (string) lang
     */
    public function getLocale(){
        return $this->locale;
    }

    /**
     * get where
     * @return (string) where
     */
    public function getWhere(){
        return Sessiion::get('where', 'rebeauty');
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout() {
        if (!is_null($this->layout)) $this->layout = View::make($this->layout);
    }

    public function permissionFilter($name=null) {
        $className = str_replace('controller', '', strtolower(get_called_class()));
        $permissionList = array('system', 'admin', $className);
        if ($name!=null)
            $permissionList[] = $name;

        $this->beforeFilter(function () use ($permissionList) {
            if (!Sentry::getUser()->hasAnyAccess($permissionList))
                return Redirect::route('admin.index');
        }, array('except' => isset($this->filterExcept) ? $this->filterExcept : array()));
    }
}
