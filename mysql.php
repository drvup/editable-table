<?PHP

class DB_MySQL {

    private $connection = NULL;
    private $result = NULL;
    private $counter = NULL;
    private $host = NULL;
    private $user = NULL;
    private $pass = NULL;
    private $database = NULL;
    private $lastIn = NULL;

    public function __construct($h = NULL, $u = NULL, $p = NULL, $d = NULL) {
        $this->host = $h;
        $this->user = $u;
        $this->pass = $p;
        $this->database = $d;
    }

    private function connect() {
        $this->connection = mysql_connect($this->host, $this->user, $this->pass);
        mysql_select_db($this->database, $this->connection);
        //mysql_query("SET NAMES utf8", $this->connection);
    }

    private function disconnect() {
        if (is_resource($this->connection))
            mysql_close($this->connection);
    }

    public function query($query) {
        $this->connect();
        $this->result = mysql_query($query, $this->connection) or die(mysql_error());
        $this->counter = NULL;
        if (strpos($query, "INSERT") !== false) {
            $this->lastIn = mysql_insert_id();
            #echo '|||'.$this->lastIn.'|||';       		
        }
        $this->disconnect();

        if (strpos($query, "INSERT") !== false)
            return $this->lastIn;
    }

    public function fetchRow() {
        return mysql_fetch_assoc($this->result);
    }

    public function count() {
        if ($this->counter == NULL && is_resource($this->result)) {
            $this->counter = mysql_num_rows($this->result);
        }
        return $this->counter;
    }

    public function escape($val) {
        $this->connect();
        return mysql_real_escape_string($val, $this->connection);
    }

    public function map($arrayIn, $tableName, $tableKeys) {
        $query = "SHOW COLUMNS FROM " . $tableName;
        //$out['insert'] = $query;
        $this->query($query);
        $numColumns = $this->count();

        /*
          $x = 0;
          while ($x < $numColumns)
          {
          $colname = $this->fetchRow();
          $arrayOut[$colname['Field']] = $arrayIn[$colname['Field']];
          $x++;
          }
         */
        //$arrayOut = array();    

        while ($row = $this->fetchRow()) {
            //array_push($arrayOut,$row['Field']);
            if (array_key_exists($row['Field'], $arrayIn)){           
                $arrayOut[$row['Field']] = $arrayIn[$row['Field']];
                
            }            
        }



        // Hole alle meine Felder aus dem Array
        foreach ($arrayOut as $feld => $wert) {
            if ($felder != NULL)
                $felder .= ', ';
            $felder .= $feld;
            if ($werte != NULL)
                $werte .= ', ';
            $werte .= "'" . $wert . "'";
            if ($updateFeldWert != NULL)
                $updateFeldWert .= ', ';
            $isKey = '';
            foreach ($tableKeys as $key) {
                if ($feld == $key)
                    $isKey = 'X';
            }
            if ($isKey == '')
                $updateFeldWert .= $feld . "='" . $wert . "'";
        }

        $insertStatement = "INSERT INTO " . $tableName . "(" . $felder . ") VALUES (" . $werte . ")";
        $updateStatement = "UPDATE " . $tableName . " SET " . $updateFeldWert;

        $out['insert'] = $insertStatement;
        $out['update'] = $updateStatement;
        $out['arrayOut'] = $arrayOut;
        return $out;
    }

}

/* Verwendung:
  //Klasse einbinden
  include_once 'mysql.php';

  //Neue Instanz der Klasse erzeugen
  $mydb = new DB_MySQL('Servername','Datenbankname','Benutzername','Passwort');

  //Abfrage schicken
  $mydb->query('SELECT * FROM tabelle');

  //Ausgabe aller Zeilen mit einer While-Schleife
  while($row = $mydb->fetchRow())
  print_r($row);

  //Anzahl Datens�tze ausgeben
  echo $mydb->count();
 */
?>