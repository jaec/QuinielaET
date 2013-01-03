<?php 
/**
 * Clase que agrega las funcionalidades basicas para acceso a la base de datos
 * (create, update, read, delete). Funciona con ADODB para postgres,
 * por lo que deberia ser facilmente portable a otros motores de bases de datos
 *
 * @abstract		Clase BASE - Clase que añade los metodos CRUD basicos
 * @author		Luis Antonio Zaldivar - Desarrollo de Sistemas SkyTel
 * @since			12/07/2006
 * @version 		1.0
 **/
 
class base {

    // VARIABLES
    
    var $_pk = "";
    var $_baseTbl = "";
    var $_baseDb;
    var $_baseRs = "";
    var $_meta;
    
    /* Agrega la funcionalidad de base de datos a las clases hijas */
    function base($tbl) {
        require_once "class.db.php";
        $this->_baseTbl = $tbl;
        $this->_baseDb = new db();
        $this->setProps();
        //		$this->_baseDb->debug = true;
    }
    
    function setPK($pk) {
        $this-> {$this->_pk} = $pk;
    }
    function getPk() {
        return $this-> {$this->_pk} ;
    }
    
    /* Carga como propiedades las columnas de la tabla definida en $base_tbl */
    function setProps() {
    
        if (!$this->_baseTbl)
            die("Especifica la tabla a trabajar");
            
        $cols = $this->_baseDb->MetaColumns($this->_baseTbl, false); // Obtiene informacion de las columnas
        
        foreach ($cols as $k=>$v) {
            $k = strtolower($k);
            if (isset($v->primary_key) && $v->primary_key == 1) // Si la clave es primaria, lo guarda en el objeto
                $this->_pk = $k;
                
            $this->_meta->$k = $v->type; // Hack para mysql, para que todo lo guarde en minuscula
            
            $this->$k = ''; //Inicializa la propiedad con el valor de la clave a vacio
        }
    }
    
    /* */
    function getProps($meta = 1) {
        $ar = get_object_vars($this);
        unset($ar['_baseTbl']); // Excepciones que no deben ser pasadas de la clase base
        unset($ar['_baseDb']);
        unset($ar['_pk']);
        unset($ar['_baseRs']);
        if ($meta)
            unset($ar['_meta']);
        return $ar;
    }
    
    function format($k, $v) {
        if ($this->_meta->$k == 'int4' || $this->_meta->$k == 'int2' || $this->_meta->$k == 'int8') {
            if (!$v)
                return $v + 0;
            else
                return $v;
        }
        if ($this->_meta->$k == 'date') {
            if (!$v)
                return "null";
            else {
            	$match = array();
           		if(preg_match("/(\d{2})\/(\d{2})\/(\d{4})/",$v, $match )){
           			$v = $match[3]."-".$match[2]."-".$match[1];
           		}
            	 return "'$v'";
            	
            }
        }
        if ($this->_meta->$k == 'time') {
            if (!$v)
                return "null";
            else
                return "'$v'";
        } else {
            return "'".trim($v," ")."'";
            //return "'$v'";
        }
        
    }
    
    /* Establece las propiedades de la clase desde un array. Util para recibir variables Post o Get */
    function setFromArray($array) {
    	//print_r($array);
        if (!is_array($array))
            return false;
        else {
            foreach ($array as $k=>$v) {
	            if (isset($this->_meta->$k) && $this->_meta->$k == 'date') {
		            if (!$v)
		                 $this->$k = "$v";
		            else {
		            	$match = array();
		           		if(preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/",$v, $match )){
		           			$v = $match[3]."/".$match[2]."/".$match[1];
		           		}
		            	 $this->$k = "$v";
		            	
		            }
	       		}else {
	                if (isset($this->$k)) {
	                    $this->$k = $v."";
	                }
	       		}
            }
            return true;
        }
    }
    
    /* */
    function save($format = false) {
    
        $accion = "";
        
        if ($this-> {$this->_pk} ) { // Si la clave primaria tiene valor diferente a cero
            $prop = $this->getProps();
            $values = array();
            foreach ($prop as $k=>$v) {
                if ($k == $this->_pk) // No modifica la llave primaria
                    $id = $v;
                
                if (get_magic_quotes_gpc()) $v = stripslashes($this->$k);
                else $v = $this->$k;
                //$v = mysql_real_escape_string($this->$k); // Espartaco tiene php 4.1!!!
                $v = mysql_real_escape_string($v);
                //$v = $this->$k;
                if ($format == true)
                    $v = ProcessText::ToUpperNoAccents($v);
                $values[] = $k." = ".$this->format($k, $v);
            }
            $query = "update ".$this->_baseTbl." set ".implode(", ", $values)." where ".$this->_pk." = ".$id;
            $accion = "update";
        } else {
            $prop = $this->getProps();
            $values = array();
            $keys = array();
            foreach ($prop as $k=>$v) { //Formatea los valores para ser ejecutados en el query
                if ($k == $this->_pk) { // Si es la clave primarian, no la inserta en el query, para que genere un autoincrement
                    continue;
                }
                //				$v = pg_escape_string($this->$k);
                //				$k = pg_escape_string($k);
                $v = mysql_real_escape_string($this->$k);
                $k = $k;
                $keys[] = $k;
                if ($format == true)
                    $v = ProcessText::ToUpperNoAccents($v);
                $values[] = $this->format($k, $v);
            }
            $query = "insert into ".$this->_baseTbl." (".implode(", ", $keys).") values (".implode(", ", $values).")";
            $accion = "insert";
        }
        //echo "SQL: ".$query."<br>";
        $ret = $this->_baseDb->Execute($query);
        
        if ($accion == "insert")
            $this-> {$this->_pk} = $this->_baseDb->Insert_ID($this->_baseTbl, $this->_pk);
            
        if (!$ret) { // *** Log basico de errores
            @require_once ("include/class.bitacora.php");

            $qerror = "\nError msg: ".$this->_baseDb->_errorMsg;
            $qerror .= "\nArchivo: ".$_SERVER['SCRIPT_FILENAME'];
            $qerror .= "\nClase: ".get_class($this);
            $qerror .= "\nQuery: ".$query;
            
            if (class_exists("bitacora") && get_class($this) != "bitacora" ) {
                //$b = new bitacora(addslashes($qerror));
                //$b->save();
            } else {
                die($qerror);
            }
            
        }
        $this->load();
        return $ret;
    }
    
    /* */
    function load($orderby = false) {
        if (!$this-> {$this->_pk} ) { // Si no existe la llave primaria, no se puede cargar
            return false;
        } else {
            $order = " ORDER BY ";
            if (!$orderby) {
                $order .= $this->_pk;
            } else {
                $order .= "$orderby";
            }
            
            $query = "select * from ".$this->_baseTbl." where ".$this->_pk." = ".$this-> {$this->_pk} .$order;
            if (!($this->_baseRs = $this->_baseDb->Execute($query)))
                return false;
            $r = $this->_baseRs->FetchRow();
            if (!$r) // Si no se encontro el registro
                return false;
            return $this->setFromArray($r);
        }
    }
    
    function loadAll($orderby = false) {
        $order = " ORDER BY ";
        if (!$orderby) {
            $order .= $this->_pk;
        } else {
            $order .= "$orderby";
        }
        $query = "select * from ".$this->_baseTbl.$order;
        if (!($this->_baseRs = $this->_baseDb->Execute($query))) {
            //echo $query;
            return NULL;
        }
        //		$r = $this->_baseDb->FetchRow();
        //		return $this->setFromArray($r);
        return true;
    }
    
    function next() {
        if ($this->_baseRs) {
            $r = $this->_baseRs->FetchRow();
            //echo "<br/>";
            //print_r($r);
            $rv = $this->setFromArray($r);
            return $rv;
        }
    }
    
    /* Busca en la tabla con los filtros dados en $array y regresa el primero. hace busqueda and por default (definido en $type) */
    function loadIf($array, $type = "and", $orderby = false, $limit = false) {
        $cond = array();
        $cond[] = "1 = 1";
        if (!is_array($array)) {
            return false; // Si no hay condiciones, regresa.
        }
        foreach ($array as $k=>$v) {
            if (!isset($this->$k))
                continue; // Si la propiedad no existe, ignora
                
            $k = mysql_real_escape_string($k);
            $v = mysql_real_escape_string($v);
            
            $cond[] = $k." = '".$v."'";
        }
        $order = " ORDER BY ";
        if (!$orderby) {
            $order .= $this->_pk;
        } else {
            $order .= "$orderby";
        }
        
        if ($limit) {
            $lq = " Limit ".$limit;
        } else
            $lq = "";
            
        $query = "select * from ".$this->_baseTbl." where ".implode(" ".$type." ", $cond).$order.$lq;
        if (($this->_baseRs = $this->_baseDb->Execute($query))) {
            return true;
        } else
            return false;
    }
    /* Busca en la tabla con los filtros dados en $array. hace busqueda and por default (definido en $type) */
    function loadIfMix($array, $type = "and", $orderby = false, $limit = false) {
        $cond = array();
        $cond[] = "1 = 1";
        if (!is_array($array)) {
            return false; // Si no hay condiciones, regresa.
        }
        foreach ($array as $k=>$v) {
            if (!isset($this->$k))
                continue; // Si la propiedad no existe, ignora
                
            $k = mysql_real_escape_string($k);
            $v = mysql_real_escape_string($v);
            
            $cond[] = $k.$v;
        }
        $order = " ORDER BY ";
        if (!$orderby) {
            $order .= $this->_pk;
        } else {
            $order .= "$orderby";
        }
        
        if ($limit) {
            $lq = " Limit ".$limit;
        } else
            $lq = "";
            
        $query = "select * from ".$this->_baseTbl." where ".implode(" ".$type." ", $cond).$order.$lq;
        if (($this->_baseRs = $this->_baseDb->Execute($query))) {
            return true;
        } else
            return false;
    }
    
    /* Busca en la tabla con los filtros dados en $array y regresa el primero. hace busqueda and por default (definido en $type) */
    function loadLike($array, $type = "or", $orderby = false, $limit = false) {
        $cond = array();
        //$cond[] = "1 = 0";
        if (!is_array($array)) {
            return false; // Si no hay condiciones, regresa.
        }
        foreach ($array as $k=>$v) {
            if (!isset($this->$k))
                continue; // Si la propiedad no existe, ignora
            $k = mysql_real_escape_string($k);
            $v = mysql_real_escape_string($v);
            $cond[] = $k." like '".$v."'";
        }
        $order = " ORDER BY ";
        if (!$orderby) {
            $order .= $this->_pk;
        } else {
            $order .= "$orderby";
        }
        
        if ($limit) {
            $lq = " Limit ".$limit;
        } else
            $lq = "";
            
        $query = "select * from ".$this->_baseTbl." where ".implode(" ".$type." ", $cond).$order.$lq;
        if (($this->_baseRs = $this->_baseDb->Execute($query))) {
            return true;
        } else
            return false;
    }

    
    /* */
    function delete() {
        $query = "delete from ".$this->_baseTbl." where ".$this->_pk." = ".$this-> {$this->_pk} ;
        if (!$this->_baseDb->Execute($query))
            return false;
        else {
            return true;
        }
    }
    function numrows() {
        return $this->_baseRs->_numOfRows;
    }
    
}


class ProcessText {


    function ToUpperNoAccents($cadena) {
        $trans = get_html_translation_table(HTML_ENTITIES); //Get the entities table into an array
        foreach ($trans as $literal=>$entity) { //Create two arrays, for accented and unaccented forms
            if (ord($literal) >= 192) { //Don't contemplate other characters such as fractions, quotes etc
                $replace[] = substr($entity, 1, 1); //Get 'E' from string '&Eaccute' etc.
                $search[] = $literal;
            }
        }
        //Get accented form of the letter
        $cadena = str_replace($search, $replace, $cadena);
        //		$cadena = str_replace("ñ","Ñ",$cadena);
        //		$cadena = str_replace("n","Ñ",$cadena);
        //		$cadena = strtoupper($cadena);
        
        return $cadena;
    }
    
}
?>
