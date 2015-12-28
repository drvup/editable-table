<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="editableTable.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>        
        <script src="editableTable.js"></script>
        <title>Test</title>
    </head>
    <body>
        <?php
            // NEEDED INCLUDE DATABASE
            include_once '../accessdefines.php';
            include_once 'mysql.php';
            include_once 'editableTable.php';

            // CONNECTION 2 DATABASE
            $maindb = new DB_MySQL("localhost", DB_USER, DB_PW, "MDB");
            
            $table = new editableTable($maindb, "tbl_upstream");
            
            echo $table->getColumnByName("ID", false, $classes, "ID Spalte");
            echo $table->getColumnByName("asanaToken", true, $classes, "Asana Token");
            
        ?>
    </body>
</html>
