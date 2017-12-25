<?php
	/**
	 * Created by PhpStorm.
	 * User: yves
	 * Date: 14/11/17
	 * Time: 10:06
	 */

	class Session
	{
		public static function is_user ( $login )
		{
			return ( !empty( $_SESSION[ 'login' ] ) && ( $_SESSION[ 'login' ] == $login ) );
		}
		public static function is_admin() {
			return (!empty($_SESSION['isAdmin']) && $_SESSION['isAdmin']==1);
		}
	}

?>