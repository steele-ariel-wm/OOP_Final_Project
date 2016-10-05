<?php
class database{
  private $host = 'localhost';
  private $user = 'root';
  private $pass = 'root';
  private $dbname = 'newBlog';
  private $dbh;
  private $error;
  private $stmt;

  public function __construct(){
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;

    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    try{
      $this->dbh = new PDO($dsn, $this->user,$this->pass, $options);
    } catch (PDOException $e){
      $this->error = $e->getMessage();
      echo $this->error;
    }
  }
  public function query ($query){
    $this->stmt = $this->dbh->prepare($query);
  }
  public function bind ($param, $value, $type = null){
    if (is_null($type)){
      switch(true){
        case is_int($value):
        $type = PDO::PARAM_INT;
        break;

        case is_bool($value):
        $type = PDO::PARAM_BOOL;
        break;

        case is_null($value):
        $type = PDO::PARAM_NULL;
        break;

        default:
        $type = PDO::PARAM_STR;
      }
    }
    $this->stmt->bindValue($param, $value, $type);
  }
  public function execute(){
    return $this->stmt->execute();
  }
  public function lastInsertId(){
    $this->dbh->lastInsertId();
  }
  public function resultset(){
    $this->execute();
    return $this->stmt->fetchall(PDO::FETCH_ASSOC);
  }
  public function fetch(){
    $this->stmt->execute();
    return $this->stmt->fetch();
  }
}
?>
