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
    }
    
    public function getColumnByName($name, $editable, $classes){
        $out = "";
        //print_r($this->tableData);
        // This column exists, hugh?
        if(array_key_exists($name, $this->tableData[0])){            
            foreach($this->tableData as $row){
                print_r($row);
                $out .= '<div class="'.$classes.'">'.$row[$name].'</div>';
            }
            return $out;
        }else{
            return 'This Column does not exist.';
        }                
    }
}
