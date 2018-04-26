<?php

class MySqlLeaf {
	private static $dbCon = NULL;
	private static $dbCon2 = NULL;
	private static $host = "localhost";
	private static $user = "root";
	private static $pass = "";
	private static $name = "aims";

	public static function getCon(){
		if (self::$dbCon === NULL){

			try {
				$db = mysqli_connect(
					self::$host,
					self::$user,
					self::$pass,
					self::$name
				);
			} catch(Exception $e) {
				echo $e->getMessage();
				$db = NULL;
			}

			self::$dbCon = $db;
		}

		return self::$dbCon;
	}

	public static function getCon2(){
		if (self::$dbCon2 === NULL){
			try {
				$db = new mysqli(self::$host,self::$user,self::$pass,self::$name);;
			} catch(Exception $e) {
				echo $e->getMessage();
				$db = NULL;
			}
			self::$dbCon2 = $db;
		}
	}

}