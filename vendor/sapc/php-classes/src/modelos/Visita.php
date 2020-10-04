<?php

Namespace TH\modelos;

use \TH\DB\Sql;
use TH\Modelo;
use \TH\Mailer;
use \TH\modelos\StatusVisita;

	class Visita extends Modelo{
	const ERROR = "Visita-Erro";
	const SUCCESS = "Visita-Sucesso";
	
		public static function listAll(){
			$sql = new Sql();
			$results = $sql->select("SELECT * FROM tb_visitas a INNER JOIN tb_statusvisitas b WHERE a.idstatus = b.idstatus ORDER BY dtvisita;");

			return $results;
		}
		public function save(){
			$sql = new Sql();
			$results = $sql->select("CALL sp_visitas_save(:idvisita, :idstatus, :desmoradorvisita,  :desemailvisita, :desnrtelvisita, :desendereco, :descomplementovisita, :descidadevisita, :desdoacao, :dtvisita, :deshorario)", 
				[
				":idvisita"=>$this->getidvisita(),
				":idstatus"=>$this->getidstatus(),
				":desmoradorvisita"=>$this->getdesmoradorvisita(),
				":desemailvisita"=>$this->getdesemailvisita(),
				":desnrtelvisita"=>$this->getdesnrtelvisita(),
				":desendereco"=>$this->getdesendereco(),
				":descomplementovisita"=>$this->getdescomplementovisita(),
				":descidadevisita"=>$this->getdescidadevisita(),
				":desdoacao"=>$this->getdesdoacao(),
				":dtvisita"=>$this->getdtvisita(),
				":deshorario"=>$this->getdeshorario()
			]);
			$this->setData($results[0]);
		}
		public function get($idvisita){
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_visitas WHERE idvisita = :idvisita", [
				':idvisita'=>$idvisita
			]);
			$this->setData($results[0]);
		}

		public function delete(){
			$sql = new Sql();
			$sql->query("DELETE FROM tb_visitas WHERE idvisita = :idvisita", [
				':idvisita'=>$this->getidvisita(),
			]);
		}
		public static function setError($msg){
			$_SESSION[Visita::ERROR] = $msg;
		}

		public static function getError(){
			$msg = (isset($_SESSION[Visita::ERROR]) && $_SESSION[Visita::ERROR]) ? $_SESSION[Visita::ERROR] : "";
			Visita::clearError();
			return $msg;
		}

		public static function clearError(){
			$_SESSION[Visita::ERROR] = NULL;
		}

		public static function setSuccess($msg){
			$_SESSION[Visita::SUCCESS] = $msg;
		}

		public static function getSuccess(){
			$msg = (isset($_SESSION[Visita::SUCCESS]) && $_SESSION[Visita::SUCCESS]) ? $_SESSION[Visita::SUCCESS] : "";
			Visita::clearSuccess();
			return $msg;

		}

		public static function clearSuccess(){
			$_SESSION[Visita::SUCCESS] = NULL;
		}

		public static function getEmail($email){
			$sql = new Sql();
			$results = $sql->select("SELECT * FROM tb_visitas WHERE desemailvisita = :email",[
				":email"=>$email
			]);
			if(count($results) === 0){
				header("Location: /admin/visitas");
				exit;
			}else{
				$data = $results[0];
			}
							
			$mailer = new Mailer($data['desemailvisita'], $data['desmoradorvisita'], "SAPC - Agendamento","agendamentoemail",[
						"name"=>$data['desmoradorvisita'],
						"data"=>$data['dtvisita'],
						"horario"=>$data['deshorario'],
						"desendereco"=>$data['desendereco']
					]);

				$mailer->send();
		}
		public static function getPagina($pagina = 1, $itensPorPagina = 10)
		{

			$inicio = ($pagina - 1) * $itensPorPagina;

			$sql = new Sql();

			$results = $sql->select("
				SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_visitas a 
			INNER JOIN tb_statusvisitas b USING(idstatus) 
			ORDER BY b.idstatus
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
				FROM tb_visitas a INNER JOIN tb_visitasstatus b USING(idstatus) WHERE a.desmoradorvisita LIKE :busca OR a.idvisita LIKE :busca OR dtvisita LIKE :busca OR a.deshorario LIKE :busca ORDER BY b.idstatus
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