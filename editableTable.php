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
    private $columns = array();
    private $tableData = array();
    
    function __construct($inDB) {
        $this->db = $inDB;
    }
    
    public function setTableName($tableName, $whereClause = "1 = 1", $limit = "1000"){
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
}
