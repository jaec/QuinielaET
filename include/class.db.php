<?php
include_once("adodb/adodb.inc.php");
include_once("adodb/drivers/adodb-mysql.inc.php");

if(file_exists("include/config-local.php") || file_exists("../include/config-local.php")){
	@include_once("config-local.php");
	//Si requieren usar la conexion local, solo agreguen include/config-local.php
} else {
	class db extends ADODB_mysql{
		function db(){
			$conn = &$this;
			$conn = ADONewConnection('mysql');

			$this->user                             = "root";
			$this->password                         = "";
			$this->host                             = "localhost";
						
			$this->database                         = "quiniela";
			$this->Connect();
			//Manejo de acentos
			$this->Execute("SET NAMES 'utf8'");
			//$this->debug = true; // Debug global
			//$this->LogSQL();
		}
	}
}
?>