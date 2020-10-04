<?php
	
Namespace TH\modelos;

use \TH\DB\Sql;
use \TH\Modelo;
use \TH\modelos\Usuario;

	class Categoria extends Modelo{

		public  static function listAll(){
			$sql = new Sql();
			return $sql->select("SELECT * FROM tb_categorias ORDER BY idcategoria");
		}

		public function save(){
			$sql = new Sql();
			$results = $sql->select("CALL sp_categorias_save(:idcategoria, :descategoria)", [
				":idcategoria"=>$this->getidcategoria(),
				":descategoria"=>$this->getdescategoria()
			]);

			$this->setData($results[0]);
		}

		public function get($idcategoria){
			$sql = new Sql();
			$results = $sql->select("SELECT * FROM tb_categorias WHERE idcategoria = :idcategoria",[
				":idcategoria"=>$idcategoria
			]);	

			$this->setData($results[0]);
		}

		public function delete(){
			$sql = new Sql();
			$sql->query("DELETE FROM tb_categorias WHERE idcategoria = :idcategoria", [
				":idcategoria"=>$this->getidcategoria()
			]);
		}

		public function getProdutos($relacionados = true){
			if($relacionados === true){
				$sql = new Sql();
				return $sql->select("SELECT * FROM tb_produtos WHERE idproduto IN(SELECT a.idproduto FROM tb_produtos a INNER JOIN tb_produtoscategorias b ON a.idproduto = b.idproduto WHERE b.idcategoria = :idcategoria);", [
						":idcategoria"=>$this->getidcategoria()
					]);
			}
			else{
				$sql = new Sql();
				return $sql->select("SELECT * FROM tb_produtos WHERE idproduto NOT IN(
			SELECT a.idproduto
   			FROM tb_produtos a
    		INNER JOIN tb_produtoscategorias b ON a.idproduto = b.idproduto
			WHERE b.idcategoria = :idcategoria
		);",[
					":idcategoria"=>$this->getidcategoria()
				]);
			}
		}

		public function addProduto(Produtos $produto){
			$sql = new Sql();
			$sql->query("INSERT INTO tb_produtoscategorias (idcategoria, idproduto) VALUES(:idcategoria, :idproduto)", [
				":idcategoria"=>$this->getidcategoria(),
				":idproduto"=>$produto->getidproduto()
			]);
		}

		public function removeProduto(Produtos $produto){
			$sql = new Sql();
			$sql->query("DELETE FROM tb_produtoscategorias WHERE idcategoria = :idcategoria AND idproduto = :idproduto", [
				":idcategoria"=>$this->getidcategoria(),
				":idproduto"=>$produto->getidproduto()
			]);
		}
		public static function checkCategoriaExist($descategoria){
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_categorias WHERE descategoria = :descategoria", [
			':descategoria'=>$descategoria
		]);
		return (count($results) > 0);
	}

		public static function getPagina($pagina = 1, $itensPorPagina = 10)
		{

			$inicio = ($pagina - 1) * $itensPorPagina;

			$sql = new Sql();

			$results = $sql->select("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM tb_categorias ORDER BY descategoria
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
				FROM tb_categorias WHERE descategoria LIKE :busca OR idcategoria LIKE :busca ORDER BY descategoria
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