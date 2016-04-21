<?php
class model{
	
	protected static $_data;
	protected $db;
	
    public function __construct($p=null, $table=null) {
    	$p = $p ? $p : 'mysql';
    	$c = G($p);
    	if($c) {
    		$type = $c['type'] ? $c['type'] : 'mysql';
		    switch($type) {
		        case 'mysql':
		        	if(!$this->_table) $this->_table = $table;
		        	$c['pre'] && $this->_table = $c['pre'].$this->_table;
		        	$this->db = new core_db_mysql($p);
		        	$this->db->connect($c);
		        break;
		    }
    	} else die('The '.$p.' database parameter is not set');
    	
    }
    public function add($add, $safe=array(), $replace=false) {
    	if(empty($safe)) $safe = $this->_fields;
        if ($replace)
            $rt = $this->db->replace($this->_table, $add, $safe);
        else
            $rt = $this->db->insert($this->_table, $add, $safe);
        if (true === $rt && $add[$this->_id]) {
            $rt = $add[$this->_id];
        }
        return $rt;
    }
    public function update($update, $limit=array(), $unset=array()) {
        if (isset($update[$this->_id]) && intval($update[$this->_id])) {
            $id = intval($update[$this->_id]);
        } else $id = 0;
        if (!$id && !$id = $this->_id) return false;
        unset($update[$this->_id]);
        self::$_data[$this->_table][$id] = null;
        $r = $this->updateall("`$this->_id`=$id", $update, $limit, $unset);
        if($r) return true;
        return false;
    }
    public function edit($update, $limit=array(), $unset=false) {
        return $this->update($update, $limit, $unset);
    }
    public function updateall($where, $update, $limit=array(), $unset=array()) {
        $_set = array();
        foreach ((array)$update as $_key => $_value) {
            if ($this->_fields && !in_array($_key, $this->_fields)) continue;
            if ($limit && !in_array($_key, $limit)) continue;
            if ($_key == $this->_id) continue;
            if ($unset && in_array ($_key, $unset)) {
                $_set[] = "`$_key`=$_value";
            } else {
                $_value = $this->sqlescape($_value);
                $_set[] = "`$_key`='$_value'";
            }
        }
        if (!$_set) return false;
        else {
            $_set = implode (',' , $_set);
            $this->db->query("Update {$this->_table} set {$_set} where $where" , true);
            return true;
        }
    }
    public function remove($id) {
        if (!is_array($id)) {
            $_where = "`$this->_id`='$id'";
            self::$_data[$this->_table][$id] = null;
        } else {
            $_where = "`$this->_id` in ('" . implode ("','" , (array) $id) . "')";
            foreach ((array) $id as $v) {
                self::$_data[$this->_table][$v] = null;
            }
        }
        return $this->removeall($_where);
    }
    public function del($id) {
        return $this->remove($id);
    }
    public function removeall($where) {
        $this->db->query("Delete From `{$this->_table}` where {$where}" , true);
        return true;
    }
    public function optimize() {
        $this->db->query("OPTIMIZE TABLE `{$this->_table}`" , true);
    }
    public function fetchOne($sql) {
        return $this->db->fetchOne($sql);
    }
    public function find($id, $fields=null) {
        $id = (array)$id;
        $tf = array();
        $n = count($id);
        if ($n==1) {
            $_i = array_shift($id);
            if (self::$_data[$this->_table][$_i]) {
                return self::$_data[$this->_table][$_i];
            } else $tf = array($_i);
        } else {
            foreach($id as $v) {
                if (self::$_data[$this->_table][$v]) {
                    $rt[$v] = self::$_data[$this->_table][$v];
                } else {
                    $rt[$v] = array();
                    $tf[$v] = $v;
                }
            }
        }
        if ($fields) {
            $_field = array();
            foreach((array)$fields as $_key => $_value) {
                if ($this->_fields && !in_array($_key , $this->_fields)) continue;
                else $_field[$_key] = "$_value";
            }
            if ($_field) $_field = implode (',', $_field);
            else $_field = '*';
        } else $_field = '*';
        $tn = count($tf);
        if ($tn == 1) {
            $k = array_shift($tf);
            $where = "`{$this->_id}`='" .$k ."'";
            $rt[$k] = self::$_data[$this->_table][$k] = $this->db->fetchOne("Select $_field From {$this->_table} where $where");
        } elseif ($tn > 1) {
            $where = "`{$this->_id}` in ('".implode("','", $tf)."')";
            $row = $this->db->fetchAll("Select $_field From {$this->_table} where $where ", null, 0, $this->_id);
            foreach ((array) $row as $k => $v) {
                $rt[$k] = self::$_data[$this->_table][$k] = $v;
            }
        } else return $n == 1 ? array_shift ($rt) : $rt;
        return $n == 1 ? array_shift ($rt) : $rt;
    }
	public function queryOne($fieldArr, $whereArr) {
		$sql = 'SELECT '.$this->formatFields($fieldArr).' FROM '.$this->_table.$this->formatWhere($whereArr);
		return $this->fetchOne($sql);
	}
    public function query($sql) {
        return $this->db->query($sql);
    }
    public function getCount($whereArr=array()) {
		$row = $this->queryOne('COUNT(*) row_count', $whereArr);
		return $row['row_count'];
    }
    public function queryAll($whereArr=array(), $orderByArr=array(), $limitArr=array()) {
		$fieldArr = '*';
		$start = 0;
		$offset = 0;
		$sql = 'SELECT '.$this->formatFields($fieldArr).' FROM '.$this->_table.$this->formatWhere($whereArr).$this->formatOrderBy($orderByArr);
		if($limitArr && 2==count($limitArr)) list($start, $num) = (array)$limitArr;
		return $this->db->fetchAll($sql, $start, $num, $this->_id);
    }
    public function getAll($sql='', $start=0, $num=null, $rid=null, $callback=null) {
        if (strlen($sql)==0) {
            $sql = "SELECT * FROM `" . $this->_table() . "` ";
        }
        return $this->db->fetchAll($sql, $start, $num, $this->_id);
    }
    public function getList($whereArr=array(), $orderby='', $fields='') {
        if (strlen($fields)==0) $sql = "SELECT * FROM `".$this->_table."` ";
        	else $sql = "SELECT ".$fields." FROM `{$this->_table}` ";
        if(is_array($whereArr)) $sql .= $this->formatWhere($whereArr);
        	else $sql .= " WHERE ".$whereArr;
        if (strlen ($orderby) > 0) $sql .= "ORDER BY " . $orderby;
        return $this->getAll($sql);
    }
	public function formatFields($fieldArr) {
		return !empty($fieldArr) ? implode(', ', (array)$fieldArr) : '*';
	}
	public static function formatWhere($whereArr) {
		$where = '';
		if(!empty($whereArr)) {
			foreach ((array)$whereArr as $value) {
				list($prefix, $suffix) = (!empty($value[2]) && strtoupper($value[2])=='LIKE') ? array('%', '%') : array('', '');
				$where .= (empty($where)?' WHERE ':' AND ').$value[0].' '.(empty($value[2])?'=':$value[2])." '".$prefix.self::sqlescape($value[1]).$suffix."' ";
			}
		}
		return $where;
	}
	public function formatOrderBy($orderByArr) {
		return !empty($orderByArr) ? ' ORDER BY '.implode(', ', (array)$orderByArr) : '';
	}
	public static function sqlescape($str) {
		if (is_array($str)) {
			foreach ($str as $_key => $_var) {
				$str[$_key] = $this->sqlescape($_var);
			}
			return $str;
		} else {
			return addslashes($str);
		}
	}
}