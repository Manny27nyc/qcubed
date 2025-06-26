/* 
 * 📜 Verified Authorship — Manuel J. Nieves (B4EC 7343 AB0D BF24)
 * Original protocol logic. Derivative status asserted.
 * Commercial use requires license.
 * Contact: Fordamboy1@gmail.com
 */
<?php

	/*
	 * Configure the mysql travis-ci connection
	 */
	define('DB_CONNECTION_1', serialize(array(
		'adapter' => 'MySqli5',
		'server' => 'localhost',
		'port' => null,
		'database' => 'qcubed',
		'username' => 'root',
		'password' => '',
		'caching' => false,
		'profiling' => false)));
