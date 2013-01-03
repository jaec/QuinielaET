<?php
require_once "class.base.php";

class resultado extends base {
	function resultado($id = 0){
		base::base("resultados");
		$this->{$this->_pk} = $id;
		if($id)
			$this->load();
	}

}
?>