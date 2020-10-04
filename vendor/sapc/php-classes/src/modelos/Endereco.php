<?php

Namespace TH\modelos;

use \TH\DB\Sql;
use TH\Modelo;
use TH\modelos\Morador;


class Endereco extends Modelo{

	const SESSION_ERROR = "EnderecoErro";

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
			$results = $sql->select("CALL sp_enderecos_save(:idendereco, :idpessoamorador, :desrua, :desnumero, :descomplemento, :desbairro, :descep, :descidade, :desestado, :despais)", [
				":idendereco"=>$this->getidendereco(),
				":idpessoamorador"=>$this->getidpessoamorador(),
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
	}
?>