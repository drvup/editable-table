<?php
/**
 * Project 'editable-table'
 * The test "index" file
 *
 * @category editableTable
 * @author dr_vup aka Cedric
 * @company FHR Websolutions GbR
 * @version 0.9
 * @date 28.12.2015
 */
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="editableTable.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>        
        <script src="editableTable.js"></script>
        <title>Editable Table - Easy customizing of Database Tables</title>
    </head>
    <body>
        <?php
            // NEEDED INCLUDE DATABASE
            include_once '../accessdefines.php';
            include_once 'mysql.php';
            include_once 'editableTable.php';

            // CONNECTION 2 DATABASE (this is an own used mysql class, you can also use mysqlii :))
            $maindb = new DB_MySQL("localhost", DB_USER, DB_PW, "MDB");            
            
            // Example 4 editable Table:
                // Create a new instance 4 a database table (you're also able to limit this response or to set a where clause)
                $table = new editableTable($maindb, "tbl_upstream");

                // To receive change requests from js, we need to implement this if area:
                if($_GET['edit']){
                    $table->setNewValue($_POST['columnName'], $_POST['tableKeys'], $_POST['newValue'], $_POST['token']);
                }
                
                // All columns you need - just insert a new one if you want - also you can set do:
                    // a) activate / deactive the editable funciton of this column
                    // b) add your own class to this column
                    // c) deliver your own name of this column
                echo $table->getColumnByName("ID", false, $classes, "ID Spalte");
                echo $table->getColumnByName("asanaToken", true, $classes, "Asana Token");
            
        ?>
    </body>
</html>
