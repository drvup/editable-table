# editable-table

### Description
Customizing tables are the perfect way to organise your settings.
But you never include this tables in your repository because it's to much work for something like an customizing table, who an admin will maybe consult 2 times a year?

Here is the solution :) 

With this development it's easy to include tables in your PHP project.

### To Dos
Just do the following:
*  1) Make an class instance by suppling the table name - if you want, 
      you're able to send also an where clause and set a limit for showed entrys
*  2) With the function getColumnByName() you can choose, which columns you will present 

### Using


### Example
    // First, generate an instance 
    $table = new editableTable($database_instance, "tablename", "WHERE clause = 1", "100");

    // To receive change requests from js, we need to implement this "if area":
    if($_GET['edit']){
        $table->setNewValue($_POST['columnName'], $_POST['tableKeys'], $_POST['newValue'], $_POST['token']);
    }
    $classes = "myExtraClassesIMade4ThisTutorial";
    echo $table->getColumnByName("ID", false, $classes, "ID Spalte");
    echo $table->getColumnByName("fieldname", true, $classes, "Tabellenzeilen Bezeichner");