<?php
namespace aesthetics;

/*
 * This controller is used to handle request of wintness
 */
class TestController extends \BaseController {
    public function getChange(){
        $wintness = \Wintness::get();

        foreach($wintness as $r){

            //$services = json_decode($r->label_service, true);
            /*$faqs = json_decode($r->label_faq, true);
            foreach($faqs as $service){
                \WintnessLabels::create(array('wid'=>$r->id, 'label_id'=>$service));
            }*/
            /*
            $tabs = json_decode($r->tabs);
            $order = 1;
            foreach($tabs as $tab){
                \Tabs::create(array('type'=>'wintness', 'item_id'=>$r->id, 'title'=>$tab->title, 'content'=>$tab->content, 'sort'=>$order++));
            }*/

        }
    }

    /*
     * handle garbage collection
     */
    public function getGarbageCollect(){
        $dir = implode(DIRECTORY_SEPARATOR, array(storage_path(), 'files', 'cache'));
        $this->removeDir($dir);
        echo 'finished clear cache of <br />' . $dir . '<br />';
        $dir = implode(DIRECTORY_SEPARATOR, array(storage_path(), 'cache'));
        $this->removeDir($dir);
        echo 'finished clear cache of <br />' . $dir . '<br />';
        $dir = implode(DIRECTORY_SEPARATOR, array(storage_path(), 'views'));
        $this->removeDir($dir);
        echo 'finished clear cache of <br />' . $dir . '<br />';
        \Cache::flush();
        \Session::flush();
        exit;
    }

    private function removeDir($dir, $bool=false){
        if (is_dir($dir)){
            $dirList = scandir($dir);
            if (sizeof($dirList)==2 && $bool)
                return rmdir($dir);
            else{
                foreach($dirList as $file){
                    if ($file!='.' && $file!='..' && $file!='.gitignore'){
                        $filename = $dir . DIRECTORY_SEPARATOR . $file;
                        $this->removeDir($filename, true);
                    }
                }

                if ($bool)
                    return rmdir($dir);
            }
        }else{
            return unlink($dir);
        }
    }
}