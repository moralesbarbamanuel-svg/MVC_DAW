<?php
// Wrapper minimal que emula la interfaz usada por la aplicación
// Implementa internamente mysqli (no PDO)
class SPDO
{
    private static $instance = null;
    private $mysqli;

    private function __construct()
    {
        if (!defined('DB_HOST')) {
            if (file_exists(__DIR__ . '/config.php')) {
                require_once __DIR__ . '/config.php';
            }
        }

        $host = defined('DB_HOST') ? DB_HOST : 'metro.proxy.rlwy.net:54578/railway';
        $user = defined('DB_USER') ? DB_USER : 'root';
        $pass = defined('DB_PASS') ? DB_PASS : '';
        $db = defined('DB_NAME') ? DB_NAME : '';

        $this->mysqli = new mysqli($host, $user, $pass, $db);
        if ($this->mysqli->connect_errno) {
            die('Error de conexión a la base de datos: ' . $this->mysqli->connect_error);
        }
        // Asegurar charset
        if (defined('DB_CHARSET')) {
            $this->mysqli->set_charset(DB_CHARSET);
        }
    }

    // Devuelve un wrapper que implementa prepare()/etc como espera el código
    public static function singleton()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Prepara una consulta y devuelve un objeto SPDOStatement
    public function prepare($sql)
    {
        return new SPDOStatement($this->mysqli, $sql);
    }

}

class SPDOStatement
{
    private $mysqli;
    private $sql;
    private $stmt;
    private $result;
    private $params = array();
    private $fetchMode = null;
    private $fetchClass = null;

    public function __construct($mysqli, $sql)
    {
        $this->mysqli = $mysqli;
        $this->sql = $sql;
    }

    // Emula bindParam(1, $var)
    public function bindParam($index, &$var)
    {
        $this->params[(int) $index] = &$var;
    }

    // Ejecuta la consulta. Si hay parámetros usa prepared statements
    public function execute()
    {
        if (count($this->params) > 0) {
            $this->stmt = $this->mysqli->prepare($this->sql);
            if ($this->stmt === false) {
                die('Error en prepare: ' . $this->mysqli->error);
            }

            // Construir tipos y argumentos para bind_param
            $types = '';
            $args = array();
            ksort($this->params);
            foreach ($this->params as $p) {
                if (is_int($p))
                    $types .= 'i';
                elseif (is_float($p))
                    $types .= 'd';
                else
                    $types .= 's';
                $args[] = &$p;
            }

            if (!empty($args)) {
                array_unshift($args, $types);
                call_user_func_array(array($this->stmt, 'bind_param'), $args);
            }

            if (!$this->stmt->execute()) {
                die('Error en execute: ' . $this->stmt->error);
            }

            // Intentar obtener resultado (requiere mysqlnd)
            $res = $this->stmt->get_result();
            $this->result = $res;
        } else {
            $res = $this->mysqli->query($this->sql);
            if ($res === false) {
                die('Error en query: ' . $this->mysqli->error);
            }
            $this->result = $res;
        }

        return true;
    }

    // Emula fetchAll(PDO::FETCH_CLASS, "ClassName")
    public function fetchAll($mode = null, $className = null)
    {
        $rows = array();
        if ($this->result instanceof mysqli_result) {
            $rows = $this->result->fetch_all(MYSQLI_ASSOC);
        }

        if ($mode === defined('PDO') ? PDO::FETCH_CLASS : (defined('PDO') ? PDO::FETCH_CLASS : 8) || $mode === (defined('PDO') ? PDO::FETCH_CLASS : 8) || $className !== null) {
            $objs = array();
            foreach ($rows as $row) {
                $ref = new ReflectionClass($className);
                $obj = $ref->newInstanceWithoutConstructor();
                foreach ($row as $k => $v) {
                    if ($ref->hasProperty($k)) {
                        $prop = $ref->getProperty($k);
                        $prop->setAccessible(true);
                        $prop->setValue($obj, $v);
                    } else {
                        $obj->$k = $v;
                    }
                }
                $objs[] = $obj;
            }
            return $objs;
        }

        return $rows;
    }

    // Emula setFetchMode(PDO::FETCH_CLASS, "ClassName")
    public function setFetchMode($mode, $className = null)
    {
        $this->fetchMode = $mode;
        $this->fetchClass = $className;
    }

    // Emula fetch() cuando se ha seteado FETCH_CLASS
    public function fetch()
    {
        if ($this->result instanceof mysqli_result) {
            $row = $this->result->fetch_assoc();
        } else {
            $row = null;
        }

        if ($row === null)
            return null;

        if ($this->fetchMode === (defined('PDO') ? PDO::FETCH_CLASS : 8) && $this->fetchClass !== null) {
            $ref = new ReflectionClass($this->fetchClass);
            $obj = $ref->newInstanceWithoutConstructor();
            foreach ($row as $k => $v) {
                if ($ref->hasProperty($k)) {
                    $prop = $ref->getProperty($k);
                    $prop->setAccessible(true);
                    $prop->setValue($obj, $v);
                } else {
                    $obj->$k = $v;
                }
            }
            return $obj;
        }

        return $row;
    }

}

?>
