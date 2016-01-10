<?php

namespace Ark4ne\Processes\System\OS;

use Ark4ne\Processes\Exception\OSUnknownException;

class Manager
{
	const OS_UNKNOWN = 1;
	const OS_WIN = 2;
	const OS_LINUX = 3;
	const OS_OSX = 4;

	/**
	 * @var OSInterface
	 */
	private static $os;

	/**
	 * @return int
	 */
	static private function getOS()
	{
		switch (true) {
			case stristr(PHP_OS, 'DAR'):
				return self::OS_OSX;
			case stristr(PHP_OS, 'WIN'):
				return self::OS_WIN;
			case stristr(PHP_OS, 'LINUX'):
				return self::OS_LINUX;
			default :
				return self::OS_UNKNOWN;
		}
	}

	/**
	 * @return bool
	 */
	static public function isWin()
	{
		return self::getOS() == self::OS_WIN;
	}

	/**
	 * @return bool
	 */
	static public function isLinux()
	{
		return self::getOS() == self::OS_LINUX;
	}

	/**
	 * @return bool
	 */
	static public function isOsx()
	{
		return self::getOS() == self::OS_OSX;
	}

	/**
	 * @return OSInterface|OSLinux|OSWindows
	 * @throws OSUnknownException
	 */
	static public function os()
	{
		if (self::$os == null) {
			switch (self::getOS()) {
				case self::OS_OSX :
				case self::OS_LINUX :
					self::$os = new OSLinux();
					break;
				case self::OS_WIN :
					self::$os = new OSWindows();
					break;
				default:
					throw new OSUnknownException();
			}
		}

		return self::$os;
	}
}