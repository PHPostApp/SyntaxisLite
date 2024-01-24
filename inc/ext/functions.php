<?php 

if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

if(file_exists(TS_ROOT . 'config.inc.php')) {
    require_once TS_ROOT . 'config.inc.php';
    if($db['hostname'] == 'dbhost') header("Location: ./install/index.php");
} else header("Location: ./install/index.php"); 

/**
 * Nueva forma de conectar a la base de datos
 */
try {
   /**
     * Nueva forma de conectar a la base de datos
     * Realizamos la conexión con MySQLi
     * @link https://www.php.net/manual/es/mysqli.construct.php
    */
   $db_link = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
   // Comprobar el estado de la conexión
   if ($db_link->connect_errno) {
      throw new Exception('No se pudo establecer la conexión con la base de datos.</p> <p class="warning">' . $db_link->connect_error);
   }
   // Establecer el juego de caracteres utf8
   if (!$db_link->set_charset('utf8')) {
      throw new Exception('No se pudo establecer la codificación de caracteres.');
   }
} catch (Exception $e) {
   exit(show_error($e->getMessage(), 'Sin conexión'));
}

/**
 * Ejecutar consulta
 */
function db_exec() {    
    global $db_link, $tsUser, $tsAjax, $display;

    if(isset(func_get_args()[0])) $info = func_get_args()[0];
    if(isset(func_get_args()[1])) $type = func_get_args()[1];
    if(isset(func_get_args()[2])) $data = func_get_args()[2];
     
    // Si la primera variable contiene un string, se entiende que es la consulta que debe ejecutarse. Esto lo prepara para ello.
    if(is_array($info)) {
        if(!$tsUser->is_admod && $display['msgs'] != 2) { 
            $info[0] = explode('\\', $info[0]); 
        }
        $info['file'] = $tsUser->is_admod || $display['msgs'] == 2 ? $info[0] : end($info[0]);
        $info['line'] = $info[1];
        $info['query'] = $data;
    } else {
        $data = $type;
        $type = $info;
        if($type == 'query') { 
            $info = []; 
            $info['query'] = $data; 
        }
    }
     
    if($type === 'query' && !empty($data)) {
        $query = mysqli_query($db_link, $data);
        if(!$query && !$tsAjax && $display['msgs'] && ($info['file'] || $info['line'] || ($info['query'] && $tsUser->is_admod))) exit( show_error( 'No se pudo ejecutar una consulta en la base de datos.', 'db', $info ) );
        return $query;
    } elseif($type === 'real_escape_string') {
        return mysqli_real_escape_string($db_link, $data);
    } elseif($type === 'num_rows') {
        return mysqli_num_rows($data);
    } elseif($type === 'fetch_assoc') {
        return mysqli_fetch_assoc($data);
    } elseif($type === 'fetch_array') {
        return mysqli_fetch_array($data);
    } elseif($type === 'fetch_row') {
        return mysqli_fetch_row($data);
    } elseif($type === 'free_result') {
        return mysqli_free_result($data);
    } elseif($type === 'insert_id') {
        return mysqli_insert_id($db_link);
    } elseif($type === 'error') {
        return mysqli_error($db_link);
    }
}

/**
 * Cargar resultados
*/
function result_array($result) {
   $result instanceof mysqli_result;
   if( !is_a($result, 'mysqli_result') ) return [];
   $array = [];
   while($row = db_exec('fetch_assoc', $result)) $array[] = $row;
   return $array;
}

/**
 * Mostrar error con diseño comprimido y agradable en pantalla
 */
function show_error($error = 'Indefinido', $type = 'db', $info = []) {
    global $db_link, $tsUser, $display;

    if($type === 'db') {
        // Definir bloques HTML
        $extra['file'] = isset($info['file']) ? '<tr><td>Archivo</td><td>'.$info['file'].'</td></tr>' : '';
        $extra['line'] = isset($info['line']) ? '<tr class="alt"><td>L&iacute;nea</td><td>'.$info['line'].'</td></tr>' : '';
        $extra['query'] = isset($info['query']) && ($tsUser->is_admod || $display['msgs'] == 2) ? '<tr><td>Sentencia</td><td>'.$info['query'].'</td></tr>' : '';
        $extra['error'] = mysqli_error($db_link) && ($tsUser->is_admod || $display['msgs'] == 2) ? '<tr><td colspan="2"><p class="warning">'.mysqli_error($db_link).'</p></td></tr>' : '';
        // Definir tabla HTML
        $table = '<table border="0"><tbody>' . $extra['file'] . $extra['line'] . $extra['query'] . $extra['error'] . '</tbody></table>';
    }
 
    $table = ($type === 'db') ? $table : '';
   $title = ($type === 'db') ? "Base de datos" : $type;
   return "<head><meta charset=\"UTF-8\" /><link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"><link href=\"https://fonts.googleapis.com/css2?family=Poppins&display=swap\" rel=\"stylesheet\"><title>Error › {$title}</title><style type=\"text/css\">*,*::after,*::before{padding:0;margin:0;box-sizing: content-box;}html{background:#EEE;}html,body{width:100%;height:100vh;}body{display:grid;justify-content:center;align-items:center;align-content:center;font-family:'Poppins',sans-serif;}#error-page{border:1px solid #CCC;background:#FFF;padding:20px;min-width:650px;max-width:780px;}#error-page h1{font-size: 28px;border-bottom: 1px solid #CCC5;padding: 6px;margin-bottom: 10px;}p.warnin {background: #FFEEEE;color: #D75454;border:1px solid #D7545455;text-align: center;padding: 10px;margin: 6px 0;}table{border:1px solid #dbe4ef;border-collapse:collapse;text-align:left;width:100%;}table td,table th{padding:5px;}table tbody td{padding:10px;color:#5a5a5a;background:#FDFDFD;border-bottom:1px solid #f3f3f3;font-weight:normal;}table tbody .alt td{background:#E1EEf4;color:#00557F;}table tbody td:first-child{border-left: none;width: 10%;font-weight: bold;border-right: 1px solid #DFDFDF}table tbody tr:last-child td{border-bottom:none;font-weight: normal; }</style></head><body><div id=\"error-page\"><h1>{$title}</h1>{$error}{$table}</div></body>";
}

// Borramos la variable por seguridad
unset($db);

/**
 * Función safe_count
 * @author Miguel92 
 * Actua igual que is_countable, excepto que este devuelve 
 * el valor y no un booleano
*/
if (!function_exists('safe_count')) {
   function safe_count($data, $mode = COUNT_NORMAL) {
      return (is_array($data) || $data instanceof Countable) ? count($data, $mode) : 0;
   }
}