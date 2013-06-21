<?php

	/**
	 * Class casting
	 * Inspired from http://stackoverflow.com/questions/2226103/how-to-cast-objects-in-php/9812023#9812023
	 *
	 * @param string|object $destination
	 * @param object $sourceObject It's type can be inaccessible (__PHP_Incomplete_Class_Name)
	 * @param string[] $sourceProperties It's type a get_object_vars($sourceObject)
	 * @return object
	 */
	function cast($destination, $sourceObject, $sourceProperties = null)
	{
		if (is_string($destination)) {
			$destination = new $destination();
		}
		if (!$sourceProperties) {
			$sourceProperties = get_object_vars($sourceObject);
		}
		// if table names in different databases are the same, there is no __PHP_Incomplete_Class_Name property
//		if (!isset($sourceProperties['__PHP_Incomplete_Class_Name'])) {
//			return $sourceObject;
//		}
		$destinationReflection = new ReflectionObject($destination);
		foreach ($sourceProperties as $name => $value) {
			if ($destinationReflection->hasProperty($name)) {
				$propDest = $destinationReflection->getProperty($name);
				$propDest->setAccessible(true);
				$propDest->setValue($destination,$value);
			}
		}
		return $destination;
	}
	
	/**
	 * Cache provider based on Memcache
	 */
	class QCacheProviderMemcache extends QAbstractCacheProvider {
		/** @var Memcache */
		protected $objMemcache;

		/**
		 * Construct the Memcache based cache provider
		 * @param array $objOptionsArray array of server options. Each item in the array contains an associative
		 * arrays with options for the server to add to memcache
		 */
		public function __construct($objOptionsArray) {
			$this->objMemcache = new Memcache();
			foreach ($objOptionsArray as $objServerOptions) {
				$host = $objServerOptions["host"];
				$port = array_key_exists("port", $objServerOptions) ? $objServerOptions["port"] : 11211;
				$persistent = array_key_exists("persistent", $objServerOptions) ? $objServerOptions["persistent"] : true;
				$weight = array_key_exists("weight", $objServerOptions) ? $objServerOptions["weight"] : 10;
				$timeout = array_key_exists("timeout", $objServerOptions) ? $objServerOptions["timeout"] : 1;
				$retry_interval = array_key_exists("retry_interval", $objServerOptions) ? $objServerOptions["retry_interval"] : 15;
				$status = array_key_exists("status", $objServerOptions) ? $objServerOptions["status"] : true;
				$failure_callback = array_key_exists("failure_callback", $objServerOptions) ? $objServerOptions["failure_callback"] : null;
				//$timeoutms = array_key_exists("timeoutms", $objServerOptions) ? $objServerOptions["timeoutms"] : null;
				//$this->objMemcache->addserver($host, $port, $persistent, $weight, $timeout, $retry_interval, $status, $failure_callback, $timeoutms);
				$this->objMemcache->addserver($host, $port/*, $persistent, $weight, $timeout, $retry_interval, $status, $failure_callback*//*, $timeoutms*/);
			}
		}

		/**
		 * Get the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @param null|string $strClassName the class of the object in the cache that we expect
		 * @return object
		 */
		public function Get($strKey, $strClassName = null) {
			$objValue = $this->objMemcache->get($strKey);
			if (null !== $strClassName && null !== $objValue && false !== $objValue) {
//				$strClassPrefix = $strClassName::ClassPrefix;
//				$strClassSuffix = $strClassName::ClassSuffix;
				
				$strClassPrefix = "";
				$strClassSuffix = "";
				try {
					$objClassReflection = new ReflectionClass($strClassName);
					$strClassPrefix = $objClassReflection->getConstant("ClassPrefix");
					$strClassSuffix = $objClassReflection->getConstant("ClassSuffix");
				} catch (LogicException $Exception) {
				} catch (ReflectionException $Exception) {
				}

				// prefix and suffix help for the side were it is defined:
				//		found OrmObjectClass instead of PrefixOrmObjectClass expected
				if (strlen($strClassPrefix) || strlen($strClassSuffix)) {
					$objValue = cast($strClassName, $objValue);
				} else {
					// , and this helps on the other side:
					//		found PrefixOrmObjectClass instead of OrmObjectClass expected
					$sourceProperties = get_object_vars($objValue);
					if (isset($sourceProperties['__PHP_Incomplete_Class_Name'])) {
						$objValue = cast($strClassName, $objValue, $sourceProperties);
					}
				}
			}
			return $objValue;
		}

		/**
		 * Set the object into the cache with the given key
		 * @param string $strKey the key to use for the object
		 * @param object $objValue the object to put in the cache
		 * @return void
		 */
		public function Set($strKey, $objValue) {
			$this->objMemcache->set($strKey, $objValue);
		}

		/**
		 * Delete the object that has the given key from the cache
		 * @param string $strKey the key of the object in the cache
		 * @return void
		 */
		public function Delete($strKey) {
			$this->objMemcache->delete($strKey);
		}

		/**
		 * Invalidate all the objects in the cache
		 * @return void
		 */
		public function DeleteAll() {
			$this->objMemcache->flush();
			// needs to wait one second after flush.
			//  See comment on http://www.php.net/manual/ru/memcache.flush.php#81420
			sleep(1);
		}
	}

?>