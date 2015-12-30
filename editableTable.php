<?php
/**
 * Project 'editable-table'
 * The main class of this project. Contains all methods to build up the table 
 * and to supply the functions to edit fields / values. 
 *
 * @category editableTable
 * @author dr_vup aka Cedric
 * @company FHR Websolutions GbR
 * @version 0.9
 * @date 28.12.2015
 */

class editableTable {
    // Private's 
    private $db;
    private $tableName;
    private $mainToken = "0djk9c0jkq0w";
    private $columns = array();
    private $tableData = array();
    private $tablePK = array();
    private $tableEntrys;
    
    function __construct($inDB, $tableName, $whereClause = "1 = 1", $limit = "1000", $mainToken) {
        $this->db = $inDB;
        $this->tableName = $tableName;
        if(isset($mainToken)){
            $this->mainToken = $mainToken;
        }
        // setTableName and get the table data
        $this->setTableName($tableName, $whereClause, $limit);
    }
    
    /**
     * function setTableName
     * @descrip This function is setting the name of the table in private vars and 
     *          collecting the data of the table
     * @param type $tableName The name of the table, the constructor will tell us
     * @param type $whereClause If the user want, he can search for explicit clause
     * @param type $limit Limit the response is possible
     */
    private function setTableName($tableName, $whereClause, $limit){
        // get columns 
        $this->db->query("DESCRIBE ".$tableName);
        while($row = $this->db->fetchRow()){
            array_push($this->columns, $row);
        }        
        // get data 
        $whereClauseCom = " WHERE ".$whereClause;
        $this->db->query("SELECT * FROM ".$tableName . $whereClauseCom . " LIMIT " . $limit);
        while($row = $this->db->fetchRow()){
            array_push($this->tableData, $row);
        }
        // how many entrys ?
        $this->tableEntrys = count($this->tableData);
        // get the Primary Keys pls
        $this->getPrimaryKeys();
    }
    
    /**
     * function getPrimaryKeys
     * @descrip This function gets all primary keys of the transmitted tablename
     *          and saves them into a private var
     */
    private function getPrimaryKeys(){
        $this->db->query("SHOW KEYS FROM ".$this->tableName." WHERE Key_name = 'PRIMARY'");
        while($row = $this->db->fetchRow()){
            array_push($this->tablePK, $row['Column_name']);
        }                
    }
    
    /**
     * function getPKsData
     * @descrip This function builds an html statement which includes in a json format all
     *          primary keys
     * @param type $dbRow
     * @return string
     */
    private function getPKsData($dbRow){
        $tempArr = array();
        foreach ($this->tablePK as $key => $value) {
            $temp = array($value => $dbRow[$value]);
            array_push($tempArr, $temp);
        }     
        $out = 'data-tablekeys= "'. htmlentities(json_encode($tempArr)) . '"';
        return $out;
    }
    
    /**
     * function getColumnByName
     * @param type $name
     * @param type $editable
     * @param type $classes
     * @param type $dispName
     * @return string
     */
    public function getColumnByName($name, $editable, $classes, $dispName){
        $out = '<div class="etColumn">';
        //print_r($this->tablePK);        
        // This column exists, hugh?
        if(array_key_exists($name, $this->tableData[0])){            
            // First, print the dispName if requested
            if($dispName != ""){
                if($dispName != true){
                    $out .= '<div class="etColumnName">'.$name.'</div>';
                }else{
                    $out .= '<div class="etColumnName">'.$dispName.'</div>';
                }
            }
            // Print the values
            foreach($this->tableData as $row){
                $out .= '<div class="etField '.$classes.'">';
                if($editable == true){                    
                    $out .= '<span class="etEditableField" data-colname="'.$name.'" '.$this->getPKsData($row).' data-token="'.$this->getToken($name).'">'.(($row[$name] != "")? $row[$name]:"&nbsp;").'</span>';
                }else{
                    $out .= '<span class="etTextField">'.$row[$name].'</span>';
                }
                $out .= '</div>';
            }
            $out .= '</div>';
            return $out;
        }else{
            return 'This Column does not exist.';
        }                
    }
    
    /**
     * function getToken
     * @param type $columnName
     * @return type
     */
    private function getToken($columnName){
        return $this->encrypt($columnName, $this->mainToken . $columnName . "c<3d");
    }
    
    /**
     * function setNewValue
     * @param type $columnName
     * @param type $tableKeys
     * @param type $newValue
     * @param type $token
     * @return string
     */
    public function setNewValue($columnName, $tableKeys, $newValue, $token){
        if($token != ""){
            if($this->decrypt($token, $this->mainToken . $columnName . "c<3d") == $columnName){                            
                $temp = array();
                $temp = json_decode($tableKeys, 1);
                $entrys = count($temp);
                $i = 0;
                foreach($temp as $row){
                    foreach($row as $key => $value){
                        $i++;
                        $tableKeyString .= $key . " = '" . $value . "'" . (($i < $entrys)?" AND ": "");            
                    }
                }                                
                return $this->db->query("UPDATE ".$this->tableName." SET ".$columnName." = '".$newValue."' WHERE ".$tableKeyString);
            }else{
                return "Token is wrong";
            }
        }else{
            return "No token supplied";
        }
    }
    
    /**
     * Function for encrypt data
     * by using a defined key
     *
     * @author   dr_vup
     * @date     12.12.2014
     *
     * @param   string	 $string: string which should be encrypted
     * @param   string   $key: key for encrypting
     *
     * @return  string   encrypted string
     */
    private function encrypt($string, $key) {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
    }

    /**
     * Function for decrypt data
     * by using a defined key
     *
     * @author   dr_vup
     * @date     12.12.2014
     *
     * @param   string  $string string which should be decrypted
     * @param   string  $key    key for decrypting
     *
     * @return  string   decrypted string
     */
    private function decrypt($string, $key) {
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    }    
}
