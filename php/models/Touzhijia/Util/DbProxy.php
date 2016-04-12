<?php

/**
 * 数据库访问代理
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Util
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-04-01 16:45:40
 */
class Touzhijia_Util_DbProxy
{
        /**
         * @var resource db连接句柄
         */
        protected $_db;

        /**
         * @var resource PDOStatement句柄
         */
        protected $_sts;

        /**
         * @var array() db连接配置
         */
        protected $_config;

        /**
         * @var string 最近一次查询的SQL语句
         */
        protected $_lastQuery;

        /**
         * @var string 数据库名
         */
        protected $_dbName = null;


        public function __construct($dbconfig, $log_file_prefix = 'sql')
        {
                $this->_config = $dbconfig;
                $this->_log_file_prefix = $log_file_prefix;

		try {
			$this->_db = new PDO($this->_config['dsn'], $this->_config['username'], $this->_config['password']);
		} catch (PDOException $e) {
			$msg = "DSN[".$this->_config['dsn']."],Error[Connection failed: " . $e->getMessage() . "]";
			Touzhijia_Util_Log::getInstance()->log($msg, 'FATAL', $this->_log_file_prefix);
			throw new Exception(__METHOD__ . ": $msg");
		}

		if (!empty($this->_config['charset'])) {
			$sql = "SET NAMES " . $this->_config['charset'];
			$this->query($sql);
		}
        }


        /**
         * 切换数据库
         *
         * @param string $db_name 数据库名
         * @return mix 执行结果
         */
        public function selectDb($db_name)
        {
                $this->_dbName = $db_name;
                return $this->query("USE {$db_name}");	
        }


        /**
         * 根据表名查询表的字段名
         *
         * @param string @tbName 表
         * @param string @dbName 数据库
         * @return array array(字段名=>字段注释)
         */
        public function getTableFields($tbName, $dbName = null)
        {
                if (empty($dbName)) {
                        $dbName = $this->_dbName;
                }

                if (empty($dbName)) {
                        throw new Exception(__CLASS__ . __METHOD__ . " must have tbname and dbname");
                }

                //$result = $this->query("DESC $tbName");
                $sql = sprintf("Select COLUMN_NAME,  COLUMN_COMMENT from INFORMATION_SCHEMA.COLUMNS 
                                Where table_name = '%s' AND table_schema = '%s' ", $tbName, $dbName);
                $result = $this->query($sql);

                if (empty($result)) {
                        return array();;
                }

                $arrResult = array();
                foreach ($result as $row) {
                        $arrResult[$row['COLUMN_NAME']] = empty($row['COLUMN_COMMENT']) ? $row['COLUMN_NAME'] : $row['COLUMN_COMMENT'];
                }

                return $arrResult;
        }


        /**
         * 执行SQL查询
         *
         * @param string $sql SQL语句
         * @param mix $fetchType 查询结果集的字段类型
         * @return array 查询结果集
         */
        public function query($sql, $fetchType = PDO::FETCH_ASSOC)
        {
                $msg = __METHOD__ . " $sql";
                Touzhijia_Util_Log::getInstance()->log($msg, 'INFO', $this->_log_file_prefix);

                $this->_lastQuery = $sql;
                $arrResults = null;

                try {
                        $this->_sts = $this->_db->query($sql);
                        if ($this->_sts === FALSE) {
                                $msg = "PDO Query Error: [" . $sql . "]";
                                Touzhijia_Util_Log::getInstance()->log($msg, 'ERROR', $this->_log_file_prefix);
				return false;
                        }
			
			if ($this->_sts) {
                                $arrResults = $this->_sts->fetchAll($fetchType);
                        } else {
                                $arrResults = null;
                        }

                        return $arrResults;
                } catch (PDOException $e) {
                        $msg = "DNS[".$this->_config['dsn']."],PDO_Query_Error[" . $e->getMessage() . "]";
                        Touzhijia_Util_Log::getInstance()->log($msg, 'FATAL', $this->_log_file_prefix);
                }
        }


	/**
	 * 查询插入ID
	 *
	 * @return integer
	 */
        public function lastInsertId()
        {
                return $this->_db->lastInsertId();
        }


	/**
	 * 查询影响的记录行数
	 *
	 * @return integer
	 */
	public function affectedRows()
	{
		if (empty($this->_sts)) {
			return null;
		}

		return $this->_sts->rowCount();
	}


        /**
         * 获取最近一次查询的SQL语句
	 *
	 * @return string
         */
        public function getLastQuery()
        {
                return $this->_lastQuery;
        }


	/**
	 * 转义
	 *
	 * @param string
	 * @return string
	 */
        public function quote($str)
        {
                return $this->_db->quote($str);
        }


        /**
         * 执行SQL查询，返回第一行的第一列
         *
         * @param string $sql SQL语句
         * @return mix 查询结果
         */
        public function fetchOne($sql)
        {
                $result = $this->query($sql, PDO::FETCH_NUM);

                if (empty($result)) {
                        return 0;
                }

                return $result[0][0];
        }


        /**
         * 执行SQL查询，返回第一行
         *
         * @param string $sql SQL语句
         * @return mix 查询结果
         */
        public function fetchRow($sql)
        {
                $result = $this->query($sql);

                if (empty($result)) {
                        return null;
                }

                return $result[0];
        }


        /**
         * 执行SQL查询，返回key-val对，每一行的第一列作为key，第二列作为value，组成key-val对返回
         *
         * @param string $sql SQL语句
         * @return mix 查询结果
         */
        public function fetchPairs($sql)
        {
                $result = $this->query($sql, FETCH_NUM);

                if (empty($result)) {
                        return null;
                }

                $list = array();

                foreach ($result as $row) {
                        $key = $row[0];
                        $val = $row[1];
                        $list[$key] = $val;
                }

                return $list;

        }


	/**
	 * 查询数据库
	 *
	 * @param string $strTblName
	 * @param array  $arrWhere
	 * @param array  $arrWhat
	 * @param array  $arrOrder
	 * @param integer $limit
	 * @param integer $offset
	 * @return array
	 */
        public function select($strTblName, $arrWhere = null, $arrWhat = null, $arrOrder = null, $limit = null, $offset = null)
        {
                if (empty($arrWhat)) {
                        $strColumn = '*';
                } else {
                        $strColumn = "`" . implode("`,`",$arrWhat) . "`";
                }

                if (empty($arrWhere)) {
                        $strWhere = "";
                } else {
                        $strWhere  = "WHERE";
                        $bFirstJoin = true;
                        foreach ($arrWhere as $k => $v) {
                                $k = addslashes($k);
                                $v = addslashes($v);

                                $strWhere .= ($bFirstJoin ? ' ' : ' AND ') . "`$k`='$v'"; 
                                $bFirstJoin = false;
                        }
                }

                if (empty($arrOrder)) {
                        $strOrder = "";
                } else {
                        $strOrder = "ORDER BY";
                        $bFirstJoin = true;
                        foreach ($arrOrder as $k => $v) {
                                $k = addslashes($k);
                                $v = (strtoupper($v) == 'ASC') ? 'ASC' : 'DESC';
                                $strOrder .= ($bFirstJoin ? ' ' : ',') . "`$k` $v"; 
                                $bFirstJoin = false;
                        }
                }

                $strLimit = '';
                if (!is_null($limit)) {
                        $strLimit = ' LIMIT '.intval($limit);
                }

                if (!is_null($offset)) {
                        $strLimit = ' LIMIT '.intval($offset) . ',' . intval($limit);
                }

                $strTblName = addslashes($strTblName);

                $sql = "SELECT $strColumn FROM $strTblName $strWhere $strOrder $strLimit";
                return $this->query($sql);
        }


	/**
	 * 更新数据库记录
	 *
	 * @param string $strTblName
	 * @param array  $arrWhere
	 * @param array  $arrWhat
	 * @return void
	 */
        public function update($strTblName, $arrWhere = null, $arrWhat = null)
        {
                if (empty($arrWhat)) {
                        return false;
                }

                if (empty($arrWhere)) {
                        $strWhere = "";
                } else {
                        $strWhere  = "WHERE";
                        $bFirstJoin = true;
                        foreach ($arrWhere as $k => $v) {
                                $k = addslashes($k);
                                $v = addslashes($v);

                                $strWhere .= ($bFirstJoin ? ' ' : ' AND ') . "`$k`='$v'"; 
                                $bFirstJoin = false;
                        }
                }

                $first = true;
                $strUpdate = '';				
                foreach ($arrWhat as $k => $v) {
                        if (!$first) {
                                $strUpdate .= ',';
                        }

                        if (is_null($v)) {
                                $strUpdate .= "`" . addslashes($k) . "`=NULL";
                        } else {
                                $strUpdate .= "`" . addslashes($k) . "`=" . "'" . addslashes($v) . "'";
                        }

                        $first = false;
                }

                $strTblName = addslashes($strTblName);

                $sql = "UPDATE $strTblName SET $strUpdate $strWhere";
                return $this->query($sql);
        }		


	/**
	 * 插入数据库记录
	 *
	 * @param string $strTblName
	 * @param array  $arrWhat
	 * @return integer 新纪录ID
	 */
        public function insert($strTblName, $arrWhat = null)
        {
                if (empty($arrWhat)) {
                        return false;
                }

                $strTblName = addslashes($strTblName);
                $strKeyList = "`".implode("`,`",array_keys($arrWhat))."`";

                $first = true;
                $strValList = '';
                foreach ($arrWhat as $k => $v) {
                        if (!$first) {
                                $strValList .= ',';
                        }

                        if (is_null($v)) {
                                $strValList .= 'NULL';
                        } else {
                                $strValList .= "'" . addslashes($v) . "'";
                        }

                        $first = false;
                }

                $sql = "INSERT INTO $strTblName($strKeyList) VALUES($strValList) ";
                return $this->query($sql);
        }		


	/**
	 * 替换数据库记录
	 *
	 * @param string $strTblName
	 * @param array  $arrWhat
	 * @return void
	 */
        public function replace($strTblName, $arrWhat = null)
        {
                if (empty($arrWhat)) {
                        return false;
                }

                $strTblName = addslashes($strTblName);
                $strKeyList = "`".implode("`,`",array_keys($arrWhat))."`";

                $first = true;
                $strValList = '';
                foreach ($arrWhat as $k => $v) {
                        if (!$first) {
                                $strValList .= ',';
                        }

                        if (is_null($v)) {
                                $strValList .= 'NULL';
                        } else {
                                $strValList .= "'" . addslashes($v) . "'";
                        }

                        $first = false;
                }

                $sql = "REPLACE INTO $strTblName($strKeyList) VALUES($strValList) ";
                return $this->query($sql);
        }		



	/**
	 * 删除数据库记录
	 *
	 * @param string $strTblName
	 * @param array  $arrWhat
	 * @return void
	 */
        public function delete($strTblName, $arrWhere = null)
        {
                if (empty($arrWhere)) {
                        $strWhere = "";
                } else {
                        $strWhere  = "WHERE";
                        $bFirstJoin = true;
                        foreach ($arrWhere as $k => $v) {
                                $k = addslashes($k);
                                $v = addslashes($v);

                                $strWhere .= ($bFirstJoin ? ' ' : ' AND ') . "`$k`='$v'"; 
                                $bFirstJoin = false;
                        }
                }

                $strTblName = addslashes($strTblName);

                $sql = "DELETE FROM $strTblName $strWhere";
                return $this->query($sql);
        }

}

