<?php
class Conf {
   
  static private $databases = array(
    // Hostname is webinfo at IUT
    // or localhost on your computer
    'hostname' => 'localhost',
    // At IUT, you have a database named after your login
    // On your computer, please create a database
    'database' => 'dataviz',
    // At IUT, it is your classical login
    // On your computer, you should have at least a 'root' account
    'login' => 'root',
    // At IUT, it is your database password 
    // (=PHPMyAdmin pwd, INE by defaut)
    // On your computer, you created the pwd during setup
    'password' => 'azerty',
    'port'=>'3306'
  );
  
  static private $debug = True; 
    
  static public function getDebug() {
    return self::$debug;
  }
   
  static public function getLogin() {
    //in PHP, indices of arrays car be strings (or integers)
    return self::$databases['login'];
  }
  static public function getHostname() {
    //in PHP, indices of arrays car be strings (or integers)
    return self::$databases['hostname'];
  }
  static public function getPassword() {
    //in PHP, indices of arrays car be strings (or integers)
    return self::$databases['password'];
  }
  static public function getDatabase() {
    //in PHP, indices of arrays car be strings (or integers)
    return self::$databases['database'];
  }
  static public function getPort(){
    return self::$databases['port'];
  }
}
?>
