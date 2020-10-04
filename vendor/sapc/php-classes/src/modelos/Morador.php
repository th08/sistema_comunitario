<?php

Namespace TH\modelos;

use \TH\DB\Sql;
use \TH\Modelo; 

	class Morador extends Modelo{

		public static function listAll(){
			$sql = new Sql();
			return $sql->select("SELECT * FROM tb_moradores a INNER JOIN tb_pessoasmoradores b USING(idpessoamorador) ORDER BY desmorador");
		}
		public static function getCEP($nrcep)
	{
		$nrcep = str_replace("-", "", $nrcep);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://viacep.com.br/ws/$nrcep/json/");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = json_decode(curl_exec($ch), true);
		curl_close($ch);
		return $data;
	}

	public function loadFromCEP($nrcep)
	{
		$data = Endereco::getCEP($nrcep);
		if (isset($data['logradouro']) && $data['logradouro']) {
			$this->setdesrua($data['logradouro']);
			$this->setdescomplemento($data['complemento']);
			$this->setdesbairro($data['bairro']);
			$this->setdescidade($data['localidade']);
			$this->setdesestado($data['uf']);
			$this->setdespais('Brasil');
			$this->setdescep($nrcep);
		}
	}
		public function save(){
			$sql = new Sql();
			$results = $sql->select("CALL sp_moradores_save(:desmorador, :desidentidade, :descpf, :nrtel, :desemail, :nrpessoas, :desrua, :desnumero, :descomplemento, :descidade, :desestado, :despais,  :descep, :desbairro)", [
				":desmorador"=>$this->getdesmorador(),
				":desidentidade"=>$this->getdesidentidade(),
				":descpf"=>$this->getdescpf(),
				":nrtel"=>$this->getnrtel(),				
				":desemail"=>$this->getdesemail(),
				":nrpessoas"=>$this->getnrpessoas(),
				":desrua"=>$this->getdesrua(),
				":desnumero"=>utf8_decode($this->getdesnumero()),
				":descomplemento"=>$this->getdescomplemento(),
				":descidade"=>$this->getdescidade(),
				":desestado"=>$this->getdesestado(),
				":despais"=>$this->getdespais(),
				":descep"=>$this->getdescep(),
				":desbairro"=>$this->getdesbairro()
			]);
			$this->setData($results[0]);
		}

		public function get($idmorador){
			$sql = new Sql();
			$results = $sql->select("SELECT * FROM tb_moradores a INNER JOIN tb_pessoasmoradores b USING(idpessoamorador) WHERE a.idmorador = :idmorador", [
				":idmorador"=>$idmorador
			]);

			$data = $results[0];

			$data["desmorador"] = utf8_encode($data["desmorador"]);

			$this->setData($data);
		}

		public function delete(){
			$sql = new Sql();
			$sql->query("CALL sp_moradores_delete(:idmorador)", [
				":idmorador"=>$this->getidmorador()
			]);
		}

		public function update(){
			$sql = new Sql();
			$results = $sql->select("CALL sp_moradoresupdate_save(:idmorador, :desmorador, :desidentidade, :descpf, :nrtel, :desemail, :nrpessoas, :desrua, :desnumero, :descomplemento, :desbairro, :descep, :descidade, :desestado, :despais)", [
				":idmorador"=>$this->getidmorador(),
				":desmorador"=>$this->getdesmorador(),
				":desidentidade"=>$this->getdesidentidade(),
				":descpf"=>$this->getdescpf(),
				":nrtel"=>$this->getnrtel(),				
				":desemail"=>$this->getdesemail(),
				":nrpessoas"=>$this->getnrpessoas(),
				":desrua"=>$this->getdesrua(),
				":desnumero"=>$this->getdesnumero(),
				":descomplemento"=>$this->getdescomplemento(),
				":desbairro"=>$this->getdesbairro(),
				":descep"=>$this->getdescep(),
				":descidade"=>$this->getdescidade(),
				":desestado"=>$this->getdesestado(),
				":despais"=>$this->getdespais()
			]);
			$this->setData($results[0]);
		}

		 	public static function getPagina($pagina = 1, $itensPorPagina = 10)
		{

			$inicio = ($pagina - 1) * $itensPorPagina;

			$sql = new Sql();

			$results = $sql->select("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM tb_moradores INNER JOIN tb_pessoasmoradores USING(idpessoamorador) ORDER BY desmorador
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
				FROM tb_moradores a INNER JOIN tb_pessoasmoradores b USING(idpessoamorador) WHERE desmorador LIKE :busca OR descpf LIKE :busca ORDER BY desmorador
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