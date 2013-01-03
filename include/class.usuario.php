<?php
require_once "class.base.php";

class usuario extends base {
	function usuario($id = 0){
		base::base("usuarios");
		$this->{$this->_pk} = $id;
		if($id)
			$this->load();
	}

}
?>