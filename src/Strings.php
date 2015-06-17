<?php 
namespace Entere\Utils;

class Strings {
	

	/**
	 * 生成订单号.
	 * 用uniqid获取一个基于当前的微秒数生成的唯一不重复的字符串（但是他的前7位貌似很久才会发生变动，所以不用考虑可删除），取其第8到13位。但是这个字符串里面有英文字母，用ord获取他的ASCII码
	 * @return string
	 */
	public static function buildOrder()
	{
		$orderstr = date('YmdH').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
		return substr($orderstr,2);
	}

	/**
	 * 生成随机字串.
	 * @param  int
	 * @param  string
	 * @return string
	 */
	public static function buildRandom($length = 10, $charlist = '0-9a-z')
	{
		$charlist = str_shuffle(preg_replace_callback('#.-.#', function($m) {
			return implode('', range($m[0][0], $m[0][2]));
		}, $charlist));
		$chLen = strlen($charlist);
		if (function_exists('openssl_random_pseudo_bytes')
			&& (PHP_VERSION_ID >= 50400 || !defined('PHP_WINDOWS_VERSION_BUILD')) // slow in PHP 5.3 & Windows
		) {
			$rand3 = openssl_random_pseudo_bytes($length);
		}
		if (empty($rand3) && function_exists('mcrypt_create_iv') && (PHP_VERSION_ID >= 50307 || !defined('PHP_WINDOWS_VERSION_BUILD'))) { // PHP bug #52523
			$rand3 = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
		}
		if (empty($rand3) && @is_readable('/dev/urandom')) {
			$rand3 = file_get_contents('/dev/urandom', FALSE, NULL, -1, $length);
		}
		if (empty($rand3)) {
			static $cache;
			$rand3 = $cache ?: $cache = md5(serialize($_SERVER), TRUE);
		}
		$s = '';
		for ($i = 0; $i < $length; $i++) {
			if ($i % 5 === 0) {
				list($rand, $rand2) = explode(' ', microtime());
				$rand += lcg_value();
			}
			$rand *= $chLen;
			$s .= $charlist[($rand + $rand2 + ord($rand3[$i % strlen($rand3)])) % $chLen];
			$rand -= (int) $rand;
		}
		return $s;
	}


	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param $params 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	public static function array2arg($params) {
		$arg  = "";
		while (list ($key, $value) = each ($params)) {
			$arg.=$key."=".urlencode($value)."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);
		
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		
		return $arg;
	}

}