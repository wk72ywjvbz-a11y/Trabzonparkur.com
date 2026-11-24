<?php
$root='/var/www/html';
require $root.'/schema.php';
function required_env(string $key): string {
    $value=getenv($key);
    if($value===false || $value==='') throw new RuntimeException('Missing environment variable: '.$key);
    return $value;
}
$c=['host'=>required_env('MYSQLHOST'),'port'=>(int)(getenv('MYSQLPORT')?:3306),'db'=>required_env('MYSQLDATABASE'),'user'=>required_env('MYSQLUSER'),'pass'=>required_env('MYSQLPASSWORD'),'secret'=>getenv('APP_SECRET')?:hash_hmac('sha256','trabzonparkur-demo-csrf',required_env('MYSQLPASSWORD')),'base_url'=>getenv('APP_URL')?:''];
$email=required_env('DEMO_ADMIN_EMAIL');$password=getenv('DEMO_ADMIN_PASSWORD')?:'';
if(!filter_var($email,FILTER_VALIDATE_EMAIL)||($password!==''&&(strlen($password)<12||strlen($password)>72))||strlen($c['secret'])<32) throw new RuntimeException('Invalid admin credentials or APP_SECRET.');
$p=null;
for($i=0;$i<30;$i++){try{$p=db($c);break;}catch(PDOException $e){sleep(2);}}
if(!$p) throw new RuntimeException('MySQL connection failed.');
migrate($p);
if($password!==''&&!(int)$p->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn()){
    $p->prepare("INSERT INTO users(name,email,password_hash,role,verified) VALUES(?,?,?,'admin',1)")->execute(['Demo Yönetici',$email,password_hash($password,PASSWORD_DEFAULT)]);
}
$path=$root.'/config.php';
if(file_put_contents($path,"<?php\nreturn ".var_export($c,true).";\n",LOCK_EX)===false) throw new RuntimeException('Could not write configuration.');
chmod($path,0640);chown($path,'www-data');chgrp($path,'www-data');
