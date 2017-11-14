<?php
	/**
	 * Created by PhpStorm.
	 * User: yves
	 * Date: 14/11/17
	 * Time: 09:00
	 */

	class Security
	{
		private static $seed = 'h6drhgfpN5';

		static public function getSeed ()
		{
			return self ::$seed;
		}

		static function chiffrer($texte_en_clair) {
			$texte_en_clair=$texte_en_clair.self::getSeed ();
			$texte_chiffre = hash('sha256', $texte_en_clair);
			return $texte_chiffre;
		}
	}

?>