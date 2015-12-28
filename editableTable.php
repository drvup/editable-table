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
    private $columns = array();
    private $tableData = array();
    private $tablePK = array();
    private $tableEntrys;
    
    function __construct($inDB, $tableName, $whereClause = "1 = 1", $limit = "1000") {
        $this->db = $inDB;
        $this->tableName = $tableName;
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
        $out = 'data-tablekey="'. json_encode($tempArr) . '"';
        return $out;
    }
    
    public function getColumnByName($name, $editable, $classes, $dispName){
        $out = "";
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
                    $out .= '<input type="text" class="etEditableField" id="" data-colname="'.$name.'" '.$this->getPKsData($row).' value="'.$row[$name].'">';
                }else{
                    $out .= '<span class="etTextField">'.$row[$name].'</span>';
                }
                $out .= '</div>';
            }
            return $out;
        }else{
            return 'This Column does not exist.';
        }                
    }
}
