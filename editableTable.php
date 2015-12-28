<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of editableTable
 *
 * @author cedricheisel
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
    
    private function getPrimaryKeys(){
        $this->db->query("SHOW KEYS FROM ".$this->tableName." WHERE Key_name = 'PRIMARY'");
        while($row = $this->db->fetchRow()){
            array_push($this->tablePK, $row['Column_name']);
        }                
    }
    
    private function getPKsData($dbRow){
        $tempArr = array();
        foreach ($this->tablePK as $key => $value) {
            $temp = array($value => $dbRow[$value]);
            array_push($tempArr, $temp);
        }     
        $out = 'data-tablekeys= "'. htmlentities(json_encode($tempArr)) . '"';
        return $out;
    }
    
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
                    $out .= '<input type="text" class="etEditableField" id="" data-colname="'.$name.'" '.$this->getPKsData($row).' data-token="'.$this->getToken($name).'" value="'.$row[$name].'">';
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
    
    private function getToken($columnName){
        return $this->encrypt($columnName, $this->mainToken . $columnName . "c<3d");
    }
    
    public function setNewValue($columnName, $tableKeys, $newValue, $token){
        if($token != ""){
            if($this->decrypt($token, $this->mainToken . $columnName . "c<3d") == $columnName){                            
                $temp = array();
                $temp = json_decode($tableKeys, 1);
                $entrys = count($temp);
                $i = 0;
                foreach($temp as $key => $value){
                    $i++;
                    $tableKeyString .= $key . " = " . $value . (($i < $entrys)?" AND ": "");            
                }
                return $this->db->query("UPDATE ".$this->tableName." SET ".$columnName." = ".$newValue." WHERE ".$tableKeyString);
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
