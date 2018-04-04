<?php

if (!isset($_SESSION)){
	session_start();
}

class EnlistExam {
	protected static $prepared = null;
	protected static $score = 0;

	// Set Prepare Array
	public static function prepare($array){
		$_SESSION["prepared"] = $array;
		self::$prepared = $array;
	}

	public static function getPrepared() : array {
		self::$prepared= $_SESSION['prepared'];
		return self::$prepared;
	}

	public static function isPrepared(): bool{
		// Will depend on session
		if (isset($_SESSION["prepared"])) {
			self::$prepared= $_SESSION['prepared'];
			return true;
		}else{
			return false;
		}
	}

	public static function clearPrepared() {
		self::$prepared = null;
		$_SESSION["prepared"] = null;
	}

	// applicants question checker
	protected static function check($var){
		if ($var == 1){
			self::$score++;
		}
	}
	public static function checkAnswer($answerArr){
		$C1 = $answerArr['C1']; $C2 = $answerArr['C2']; $C3 = $answerArr['C3'];
		$C4 = $answerArr['C4']; $C5 = $answerArr['C5']; $C6 = $answerArr['C6'];
		$C7 = $answerArr['C7']; $C8 = $answerArr['C8']; $C9 = $answerArr['C9'];
		$C10 = $answerArr['C10']; $C11 = $answerArr['C11']; $C12 = $answerArr['C12'];
		$C13 = $answerArr['C13']; $C14 = $answerArr['C14']; $C15 = $answerArr['C15'];
		$C16 = $answerArr['C16']; $C17 = $answerArr['C17']; $C18 = $answerArr['C18'];
		$C19a = $answerArr['C19a']; $C19b = $answerArr['C19b']; $C19c = $answerArr['C19c'];
		$C19d = $answerArr['C19d']; $C20a = $answerArr['C20a']; $C20b = $answerArr['C20b'];
		$C20c = $answerArr['C20c'];

		// Check kung  tama ang mga simbag
		self::check($C1); self::check($C2); self::check($C3); self::check($C4);
		self::check($C5); self::check($C6); self::check($C7); self::check($C8);
		self::check($C9); self::check($C10); self::check($C11); self::check($C12);
		self::check($C13); self::check($C16); self::check($C17); self::check($C18);
		self::check($C19a); self::check($C19b); self::check($C19c); self::check($C19d);
		self::check($C20a); self::check($C20b); self::check($C20c);

		if (count($C14) == 2){
			if (count(array_unique($C14)) === 1 && end($C14) === '1'){
				self::$score++;
			}
		}

		if (count($C15) == 2){
			if (count(array_unique($C15)) === 1 && end($C15) === '1'){
				self::$score++;
			}
		}

		// START SAVING EXAMINATIONS

		return self::$score;
	}

}