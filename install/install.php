<?php
$app_path = dirname(__DIR__);

$message   =  "Install project rebeauty? [y/N]";
print $message;

$confirmation  =  trim( fgets( STDIN ) );
if ( $confirmation !== 'y' && $confirmation !== 'Y') {
   echo "\r\nCancel\r\n";
   exit (0);
}

echo "[START INSTALL]\r\n";

echo "[Check PHP Version]";

$version = '5.3.7';
if (version_compare(PHP_VERSION, $version) < 0) {
    echo "...[ERROR] ";
    echo "PHP version must >= {$version} "."\r\n";
    exit (0);
}else{
    echo "...[OK]"."\r\n";
}

echo "[Check PHP Module]";

if(!extension_loaded('mcrypt')){
    echo "...[ERROR] ";
    echo "MCrypt PHP Extension not loaded"."\r\n";
    exit (0);
}else{
    echo "...[OK]"."\r\n";
}

echo "[Create Database]";

chdir($app_path);
    
system('php artisan migrate --package=cartalyst/sentry',$result);
system('php artisan migrate',$result);

if(!$result){
    echo "...[OK]"."\r\n";
}else{
    echo "\r\nTable construct error! Please check your database config in {$app_path}/app/config/database.php\r\n";
    exit (0);
}


echo "[Default Data]";
system('php artisan db:seed',$result);
if($result){
    echo "...[OK]"."\r\n";
}else{
    exit (0);
}


echo "[Web Server Document Root]";
$file = $app_path.'/install/nginx/rebeauty';
file_put_contents($file,str_replace('DOCUMENT_ROOT',$app_path.'/public',file_get_contents($file)));
echo "...[OK]"."\r\n";


echo "\r\n"."[INSTALL COMPLETED]"."\r\n";
