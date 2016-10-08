<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think\Db\Driver;
use Think\Db;

/**
 * MSsql..... ..sqlserver2005
 */
class Mssql extends Db{
    protected $selectSql  =     '';

    private $connected;

    private $queryID;

    /**
     * .... .........
     * @access public
     * @param array $config .......
     */
    public function __construct($config=''){
        if ( !function_exists('mssql_connect') ) {
            E(L('_NOT_SUPPERT_').':mssql');
        }
        if(!empty($config)) {
            $this->config       =       $config;
            if(empty($this->config['params'])) {
                $this->config['params'] =   array();
            }
        }
    }

    /**
     * .......
     * @access public
     */
    public function connect($config='',$linkNum=0) {
        if ( !isset($this->linkID[$linkNum]) ) {
            if(empty($config))  $config  =  $this->config;
            $pconnect   = !empty($config['params']['persist'])? $config['params']['persist']:1;
            $conn = $pconnect ? 'mssql_pconnect':'mssql_connect';
            // ........socket....
            $sepr = IS_WIN ? ',' : ':';
            $host = $config['hostname'].($config['hostport']?$sepr."{$config['hostport']}":'');
            $this->linkID[$linkNum] = $conn( $host, $config['username'], $config['password']);
            if ( !$this->linkID[$linkNum] )  E("Couldn't connect to SQL Server on $host");
            if ( !empty($config['database'])  && !mssql_select_db($config['database'], $this->linkID[$linkNum]) ) {
                E("Couldn't open database '".$config['database']);
            }
            // ......
            $this->connected =  true;
            //.........
            if(1 != C('DB_DEPLOY_TYPE')) unset($this->config);
        }
        return $this->linkID[$linkNum];
    }


 /**
     * ........
     * @access protected
     * @param boolean $master ....
     * @return void
     */
    protected function initConnect($master=true) {
        if(1 == C('DB_DEPLOY_TYPE'))
            // ........
            $this->_linkID = $this->multiConnect($master);
        else
            // ......
            if ( !$this->connected ) $this->_linkID = $this->connect();
    }

    /**
     * ........
     * @access protected
     * @param boolean $master ....
     * @return void
     */
    protected function multiConnect($master=false) {
        foreach ($this->config as $key=>$val){
            $_config[$key]      =   explode(',',$val);
        }
        // .........
        if(C('DB_RW_SEPARATE')){
            // .........
            if($master)
                // ......
                $r  =   floor(mt_rand(0,C('DB_MASTER_NUM')-1));
            else{
                if(is_numeric(C('DB_SLAVE_NO'))) {// ......
                    $r = C('DB_SLAVE_NO');
                }else{
                    // .........
                    $r = floor(mt_rand(C('DB_MASTER_NUM'),count($_config['hostname'])-1));   // ..........
                }
            }
        }else{
            // ..........
            $r = floor(mt_rand(0,count($_config['hostname'])-1));   // ..........
        }
            		
        $db_config = array(
            'username'  =>  isset($_config['username'][$r])?$_config['username'][$r]:$_config['username'][0],
            'password'  =>  isset($_config['password'][$r])?$_config['password'][$r]:$_config['password'][0],
            'hostname'  =>  isset($_config['hostname'][$r])?$_config['hostname'][$r]:$_config['hostname'][0],
            'hostport'  =>  isset($_config['hostport'][$r])?$_config['hostport'][$r]:$_config['hostport'][0],
            'database'  =>  isset($_config['database'][$r])?$_config['database'][$r]:$_config['database'][0],
            'dsn'       =>  isset($_config['dsn'][$r])?$_config['dsn'][$r]:$_config['dsn'][0],
            'params'    =>  isset($_config['params'][$r])?$_config['params'][$r]:$_config['params'][0],
            'charset'   =>  isset($_config['charset'][$r])?$_config['charset'][$r]:$_config['charset'][0],
        );
        return $this->connect($db_config,$r);
    }

    /**
     * ......
     * @access public
     */
    public function free() {
        mssql_free_result($this->queryID);
        $this->queryID = null;
    }



    /**
     * ........
     * @access public
     * @param string $model  ...
     * @return void
     */
    public function setModel($model){
        $this->model =  $model;
    }



    /**
     * ..... ....SQL
     * @access protected
     */
    protected function debug() {
        $this->modelSql[$this->model]   =  $this->queryStr;
        $this->model  =   '_think_';
        // ........
        if (C('DB_SQL_LOG')) {
            G('queryEndTime');
            trace($this->queryStr.' [ RunTime:'.G('queryStartTime','queryEndTime',6).'s ]','','SQL');
        }
    }

    /**
     * ....  .....
     * @access public
     * @param string $str  sql..
     * @return mixed
     */
    public function query($str) {
        $this->initConnect(false);
        if ( !$this->_linkID ) return false;
        $this->queryStr = $str;
        //.........
        if ( $this->queryID ) $this->free();
        N('db_query',1);
        // ........
        G('queryStartTime');
        $this->queryID = mssql_query($str, $this->_linkID);
        $this->debug(true);
        if ( false === $this->queryID ) {
            $this->error();
            return false;
        } else {
            $this->numRows = mssql_num_rows($this->queryID);
            $result=$this->getAll(); 
            $this->debug(false);
            return $result;
        }
    }





   public function  queryResults($str,$fetchSql=false){

        $this->initConnect(false);
        if ( !$this->_linkID ) return false;
        $this->queryStr = $str;
        //.........
        if ( $this->queryID ) $this->free();
        N('db_query',1);
        // ........
        G('queryStartTime');
        $this->queryID = mssql_query($str, $this->_linkID);


        $this->debug(true);
        if ( false === $this->queryID ) {
            $this->error();
            return false;
        } else {
            $this->numRows = mssql_num_rows($this->queryID);
            $result = $this->getResults();
            $this->debug(false);
            return $result;
        }


   }

    /**
     * ....
     * @access public
     * @param string $str  sql..
     * @return integer
     */
    public function execute($str) {
        $this->initConnect(true);
        if ( !$this->_linkID ) return false;
        $this->queryStr = $str;
        //.........
        if ( $this->queryID ) $this->free();
           N('db_write',1);
        // ........
        G('queryStartTime');
        $result =       mssql_query($str, $this->_linkID);
        $this->debug(true);
        if ( false === $result ) {
            $this->error();
            return false;
        } else {
            $this->numRows = mssql_rows_affected($this->_linkID);
            $this->lastInsID = $this->mssql_insert_id();
            $result = $this->numRows; 
            $this->debug(false);
            return $result;
        }
    }

    /**
     * .........ID
     * @access public
     * @return integer
     */
    public function mssql_insert_id() {
        $query  =   "SELECT @@IDENTITY as last_insert_id";
        $result =   mssql_query($query, $this->_linkID);
        list($last_insert_id)   =   mssql_fetch_row($result);
        mssql_free_result($result);
        return $last_insert_id;
    }

    /**
     * ....
     * @access public
     * @return void
     */
    public function startTrans() {
        $this->initConnect(true);
        if ( !$this->_linkID ) return false;
        //..rollback ..
        if ($this->transTimes == 0) {
            mssql_query('BEGIN TRAN', $this->_linkID);
        }
        $this->transTimes++;
        return ;
    }

    /**
     * ................
     * @access public
     * @return boolen
     */
    public function commit() {
        if ($this->transTimes > 0) {
            $result = mssql_query('COMMIT TRAN', $this->_linkID);
            $this->transTimes = 0;
            if(!$result){
                $this->error();
 return false;
            }
        }
        return true;
    }

    /**
     * ....
     * @access public
     * @return boolen
     */
    public function rollback() {
        if ($this->transTimes > 0) {
            $result = mssql_query('ROLLBACK TRAN', $this->_linkID);
            $this->transTimes = 0;
            if(!$result){
                $this->error();
                return false;
            }
        }
        return true;
    }

    /**
     * .........
     * @access private
     * @return array
     */
    private function getAll() {
        //.....
        $result = array();
        if($this->numRows >0) {
            while($row = mssql_fetch_assoc($this->queryID))
                $result[]   =   $row;
        }
        return $result;

    }

   /**
     * .........
     * @access private
     * @return array
     */
    private function getResults() {
        //.....
        $result = array();
        $rowresult=0;
        if($this->numRows >0) {
         do {
              while($row = mssql_fetch_assoc($this->queryID)){
                 if ($row){
                    $result[$rowresult][] = $row;
                  }
               }
                $rowresult++;
            
         } while (mssql_next_result($this->queryID));
            		
 }

        return $result;
    }

    /**
     * ..........
     * @access public
     * @return array
     */
    public function getFields($tableName) {
        $result =   $this->query("SELECT   column_name,   data_type,   column_default,   is_nullable
        FROM    information_schema.tables AS t
        JOIN    information_schema.columns AS c
        ON  t.table_catalog = c.table_catalog
        AND t.table_schema  = c.table_schema
        AND t.table_name    = c.table_name
        WHERE   t.table_name = '$tableName'");
        $info   =   array();
        if($result) {
            foreach ($result as $key => $val) {
                $info[$val['column_name']] = array(
                    'name'    => $val['column_name'],
                    'type'    => $val['data_type'],
                    'notnull' => (bool) ($val['is_nullable'] === ''), // not null is empty, null is yes
                    'default' => $val['column_default'],
                    'primary' => false,
                    'autoinc' => false,
                );
            }
        }
        return $info;
    }

    /**
     * ..........
     * @access public
     * @return array
     */
    public function getTables($dbName='') {
        $result   =  $this->query("SELECT TABLE_NAME
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_TYPE = 'BASE TABLE'
            ");
        $info   =   array();
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }
            
                    		
    /**
     * order..
     * @access protected
     * @param mixed $order
     * @return string
     */
    protected function parseOrder($order) {
        return !empty($order)?  ' ORDER BY '.$order:' ORDER BY rand()';
    }

    /**
     * limit
     * @access public
     * @return string
     */
    public function parseLimit($limit) {
                if(empty($limit)) return '';
        $limit  =       explode(',',$limit);
        if(count($limit)>1)
            $limitStr   =       '(T1.ROW_NUMBER BETWEEN '.$limit[0].' + 1 AND '.$limit[0].' + '.$limit[1].')';
                else
            $limitStr = '(T1.ROW_NUMBER BETWEEN 1 AND '.$limit[0].")";
        return 'WHERE '.$limitStr;
    }

   /**
     * ....
     * @access public
     * @param mixed $data ..
     * @param array $options ...
     * @return false | integer
     */
    public function update($data,$options) {
        $this->model  =   $options['model'];
        $sql   = 'UPDATE '
            .$this->parseTable($options['table'])
            .$this->parseSet($data)
            .$this->parseWhere(!empty($options['where'])?$options['where']:'')
            .$this->parseLock(isset($options['lock'])?$options['lock']:false)
            .$this->parseComment(!empty($options['comment'])?$options['comment']:'');
        return $this->execute($sql);
    }

    
    /**
     * ....
     * @access public
     * @param array $options ...
     * @return false | integer
     */
    public function delete($options=array()) {
        $this->model  =   $options['model'];
        $sql   = 'DELETE FROM '
            .$this->parseTable($options['table'])
            .$this->parseWhere(!empty($options['where'])?$options['where']:'')
            .$this->parseLock(isset($options['lock'])?$options['lock']:false)
            .$this->parseComment(!empty($options['comment'])?$options['comment']:'');
        return $this->execute($sql);
    }

    /**
     * .....
     * @access public
     */
    public function close() {
        if ($this->_linkID){
            mssql_close($this->_linkID);
        }
        $this->_linkID = null;
    }

    /**
     * .......
     * ......SQL..
     * @access public
     * @return string
     */
    public function error() {
        $this->error = mssql_get_last_message();
        if('' != $this->queryStr){
            $this->error .= "\n [ SQL.. ] : ".$this->queryStr;
        }
        trace($this->error,'','ERR');
        return $this->error;
    }
}

            		
                    		
