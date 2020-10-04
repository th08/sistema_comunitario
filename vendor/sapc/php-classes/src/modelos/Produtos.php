<?php

Namespace TH\modelos;

use \TH\DB\Sql;
use \TH\Modelo;

class Produtos extends Modelo{

	public static function listAll(){
		$sql = new Sql();
		return $sql->select("SELECT * FROM tb_produtos ORDER BY desproduto;");
	}


	public function save(){
		$sql = new Sql();
		$results = $sql->select("CALL sp_produtos_save(:idproduto, :desproduto, :desdoador)",[
			":idproduto"=>$this->getidproduto(),
			":desproduto"=>$this->getdesproduto(),
			":desdoador"=>$this->getdesdoador()
		]);

		$this->setData($results[0]);
	}

	public function get($idproduto){
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_produtos WHERE idproduto = :idproduto",[
			":idproduto"=>$idproduto
		]);
		$this->setData($results[0]);
	}

	public function delete(){
		$sql = new Sql();
		$sql->query("DELETE FROM tb_produtos WHERE idproduto = :idproduto",[
			":idproduto"=>$this->getidproduto()
		]);
	}

	public static function checkProdutoExist($desproduto){
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_produtos WHERE desproduto = :desproduto", [
			':desproduto'=>$desproduto
		]);
		return (count($results) > 0);
	}

	public static function getPagina($pagina = 1, $itensPorPagina = 10)
	{

		$inicio = ($pagina - 1) * $itensPorPagina;

		$sql = new Sql();

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_produtos ORDER BY desproduto
			LIMIT $inicio, $itensPorPagina;
		");

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data'=>$results,
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'paginas'=>ceil($resultTotal[0]["nrtotal"] / $itensPorPagina)
		];

	}

	public static function getPaginaBusca($busca, $pagina = 1, $itensPorPagina = 10)
	{

		$inicio = ($pagina - 1) * $itensPorPagina;

		$sql = new Sql();

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_produtos WHERE desproduto LIKE :busca OR desdoador = :busca OR idproduto LIKE :busca ORDER BY desproduto
			LIMIT $inicio, $itensPorPagina;
		", [
			':busca'=>'%'.$busca.'%'
		]);

		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");

		return [
			'data'=>$results,
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'paginas'=>ceil($resultTotal[0]["nrtotal"] / $itensPorPagina)
		];
	} 
}


?>