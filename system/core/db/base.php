<?php
defined('SP') or exit();
class base{
	
	protected static $config = null;
	
	public static function db() {
		static $_db;
		if (isset($_db) && is_object($_db)) {
			return $_db;
		} else {
			$mysql = C('db/mysql');
			$mysql->connect(G("mysql"));
			$_db = $mysql;
			return $_db;
		}
	}
	public static function insert($table, $array, $safe=array()) {
		return self::db()->insert($table, $array, $safe);
	}
	public static function getOne($sql, $master=false) {
		return self::db()->getOne($sql, $master);
	}
	public static function fetchOne($sql) {
		return self::db()->fetchOne($sql);
	}
	public static function fetchAll($sql, $num=null, $start=0, $rid=null, $callback=null) {
		if (!$rid) {
			$rid = 'id';
		}
		return self::db()->fetchAll($sql, $num, $start, $rid, $callback);
	}
	public static function query($sql, $master=false) {
		return self::db()->query($sql, $master);
	}
	public static function replace($table, $array, $safe=array()) {
		return self::db()->replace($table, $array, $safe);
	}
	public static function update($table, $where, $array, $safe=array(), $unset=array()) {
		return self::db()->update($table, $where, $array, $safe, $unset);
	}
	public static function insertId() {
		return self::db()->insertId();
	}
	public static function sqlescape($str) {
		if (is_array($str)) {
			foreach ($str as $_key => $_var) {
				$str[$_key] = self::sqlescape($_var);
			}
			return $str;
		} else {
			return self::db()->escape($str);
		}
	}
	public static function affected_rows() {
		return self::db()->affected_rows();
	}	
	public static function fetchArray($query,$return_num=MYSQL_ASSOC) {
		return self::db()->fetchArray($query,$return_num);
	}
	public static function num_rows($query) {
		return self::db()->num_rows($query);
	}	
	public static function server_info() {
		return self::db()->server_info();
	}
}