<?php
class core_db_mysql {
	protected $link;
	protected $dbLink = array();
	protected $linkID = null;
	protected $dbname = null;
	protected $queryID = null;
	
	public function __construct($link=0) {
		$this->link = $link;
	}
	function connect($cfg='') {
		if(!isset($this->dbLink[$this->link]) ) {
			$this->linkID = $this->dbLink[$this->link] = mysql_connect($cfg['host'], $cfg['user'], $cfg['pwd'], true) or die('Connect MySQL Db Error');
			if ($this->dbname[$this->link] != $cfg['dbname']) {
				mysql_select_db($cfg['dbname'], $this->linkID) or die('Can\'t use ' . $cfg['dbname']);
				$this->dbname[$this->link] = $cfg['dbname'];
			}
			mysql_query("SET NAMES '".$cfg['charset']."'", $this->linkID);
			if(mysql_get_server_info($this->linkID) >= '5.0.1') {
				mysql_query("SET sql_mode=''", $this->linkID);
			}
		}
		return $this->linkID;
	}
	public function free() {//释放结果集
        mysql_free_result($this->queryID);
        $this->queryID = null;
	}
	function query($sql, $method='') {
        if(0===stripos($sql, 'call')) $this->close();//存储过程查询支持
		if($this->queryID) $this->free();//释放前次的查询结果
		if($method=='U_B' && function_exists('mysql_unbuffered_query')) {
			$query = mysql_unbuffered_query($sql, $this->linkID);
		} else {
			$query = mysql_query($sql, $this->linkID);
		}
		if (false === $query) $this->halt('Query Error : ' . $sql);
		return $query;
	}
	public function getOne($sql) {//快捷的获得一个字段信息
		$this->queryID = $this->query($sql);
		if ($this->queryID) {
			$row = mysql_fetch_array($this->queryID, MYSQL_NUM);
			return $row[0];
		}
		return null;
	}
	public function fetchOne($sql) {//快捷的获得一条记录
		$this->queryID = $this->query($sql);
		if ($this->queryID) {
			$row = array();
			$row = mysql_fetch_array($this->queryID, MYSQL_ASSOC);
			return $row;
		}
		return array();
	}
	public function fetchAll($sql, $start=0, $num=null, $rid=null) {//获得多条记录
		if($num) $sql = $sql.' LIMIT '.intval($start).','.$num;
		if (null === $rid) $rid = 'id';
		$this->queryID = $this->query($sql);
		if($this->queryID) {
			$re = array();
			while(($row = $this->fetchArray($this->queryID)) !== FALSE) {
				if ($rid && isset($row[$rid]) && $row[$rid])
					$re[$row[$rid]] = $row;
				else
					$re[] = $row;
			}
			return $re;
		}
		return array();
	}
    public function close() {//关闭数据库
        if ($this->linkID) mysql_close($this->linkID);
        $this->linkID = null;
    }
	public function fetchArray($query, $return_num=MYSQL_ASSOC) {
		return mysql_fetch_array($query, $return_num);
	}
	public function update($table, $where, $array, $safe=array(), $unset=array()) {
		$set = $this->createset($array, $safe, $unset);
		$sql = "Update $table Set $set Where $where";
		return $this->query($sql);
	}
	public function replace($table, $array, $safe=array()) {
		$set = $this->createset($array, $safe);
		$sql = "Replace Into $table Set $set";
		if ($resource = $this->query($sql)) {
			return ($id = $this->insert_id()) ? $id : true;
		}
		return false;
	}
	public function insert($table, $array, $safe=array()) {//insert 快捷操作
		$set = $this->createset($array, $safe);
		$sql = "Insert Into $table Set $set";
		if ($resource = $this->query($sql)) {
			return ($id = $this->insert_id(true)) ? $id : true;
		}
		return false;
	}
	public function createset($array, $safe=array(), $unset=array()) {//创建安全的set子句
		$_res = array();
		foreach ((array) $array as $_key => $_val) {
			if ($safe && !in_array($_key, $safe)) {
				continue;
			} else {
				if ($unset && in_array($_key, $unset)) {
					$_res[$_key] = "`$_key`=$_val";
				} else {
					$_val = addslashes($_val);
					$_res[$_key] = "`$_key`='$_val'";
				}
			}
		}
		return implode(',', $_res);
	}
	function insert_id($bigint = true) {//获取自增ID
		if ($bigint) {
			$r = mysql_query('Select LAST_INSERT_ID()', $this->linkID);
			$row = mysql_fetch_array($r, MYSQL_NUM);
			return $row[0];
		}
		return mysql_insert_id();
	}
	function num_rows($query) {//获取记录条数
		return mysql_num_rows($query);
	}
	function affected_rows() {//取得前一次 MySQL 操作所影响的记录行数
		return mysql_affected_rows();
	}
	function halt($s) {
		die("<div style='color:red;font-size:12px;'>$s<br /><pre>".mysql_error()."</pre></div>");
	}
}