<?php
 
 namespace TH\modelos;

use \TH\DB\Sql;
use \TH\Modelo;

class StatusVisita extends Modelo{

	const EM_ABERTO = 1;
	const ATENDIDA = 2;
	const CANCELADA = 3;

	public static function listAll(){
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_statusvisitas ORDER BY desstatus");
		return $results;
	}
}


?>