<?php
 
use App\Models\User;
 
class SentrySeeder extends Seeder {
 
    public function run()
    {
        DB::table('users')->delete();
        DB::table('groups')->delete();
        DB::table('users_groups')->delete();
 
        Sentry::getUserProvider()->create(array(
            'email'       => 'evanmo@talk2yam.com',
            'password'    => "admin",
            'last_name'   => '莫禕帆',
            'activated'   => 1,
        ));
 
        Sentry::getUserProvider()->create(array(
            'email'       => 'erichuang@talk2yam.com',
            'password'    => "eric_huang",
            'last_name'   => '黃金惇',
            'activated'   => 1,
        ));

        Sentry::getUserProvider()->create(array(
            'email'       => 'nicktsai@talk2yam.com',
            'password'    => "nick_tsai",
            'last_name'   => '蔡宜達',
            'activated'   => 1,
        ));
 
        Sentry::getGroupProvider()->create(array(
            'name'        => 'system',
            'permissions' => array('system' => 1),
        ));

        Sentry::getGroupProvider()->create(array(
            'name'        => 'admin',
            'permissions' => array('admin' => 1),
        ));

        Sentry::getGroupProvider()->create(array(
            'name'        => 'pm',
            'permissions' => array('ranks' => 1,'backend'=>1),
        ));

        // Assign user permissions
        $systemGroup = Sentry::getGroupProvider()->findByName('system');
        $adminGroup = Sentry::getGroupProvider()->findByName('admin');
        
        $adminUser  = Sentry::getUserProvider()->findByLogin('evanmo@talk2yam.com');
        $adminUser->addGroup($systemGroup);

        $adminUser  = Sentry::getUserProvider()->findByLogin('erichuang@talk2yam.com');
        $adminUser->addGroup($adminGroup);

        $adminUser  = Sentry::getUserProvider()->findByLogin('nicktsai@talk2yam.com');
        $adminUser->addGroup($adminGroup);
    
    }
}
