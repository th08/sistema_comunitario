<?php

namespace TH\modelos;
use \TH\DB\Sql;
use TH\Modelo;
use \TH\Mailer;

	class Usuario extends Modelo{

		const SESSION = "user";
		const SECRET = "SapcPhp7_Secret";
		const SECRET_IV = "SapcPhp7_Secret_IV";
		const ERROR = "UserError";
		const ERROR_REGISTER = "UserErrorRegister";
		const SUCCESS = "UserSucesss";
		const RECAPCTHA_KEY = "6LfsYL0UAAAAAEoCWovBIVFx8oZKmC3gIqbBV54-"; //Vou  ter que corrigir o erro do recapctha

		public static function login($login,$password){

			$sql = new Sql();
			$results = $sql->select("SELECT * FROM tb_usuarios a INNER JOIN tb_pessoas b ON a.idpessoa = b.idpessoa WHERE a.deslogin = :LOGIN",[
				':LOGIN'=>$login
			]);
			if(count($results) === 0){
				throw new \Exception("Login e/ou senha inválida.");
			}

			$data = $results[0];

			if(password_verify($password, $data["dessenha"]) === true){
				$user = new Usuario();
				$user->setData($data);

				$_SESSION[Usuario::SESSION] = $user->getValues();
				return $user;
			}else{
				throw new \Exception("Login e/ou senha inválida");
			}
		}

		public static function verifyLogin($inadmin = true){

			if(
				!isset($_SESSION[Usuario::SESSION])

				||

				! $_SESSION[Usuario::SESSION]

				||

				! (int)$_SESSION[Usuario::SESSION]['idusuario'] > 0

			)
			{
				header("Location:/login");
				exit;
			}

			else{
				if(
					(bool)$_SESSION[Usuario::SESSION]['inadmin'] !== $inadmin){
					header("Location: /");
					exit;
				}
			}	
		}

		public static function logOut(){
			
			$_SESSION[Usuario::SESSION] = NULL;
		}


		public static function listAll(){

			$sql = new Sql();
			return $sql->select("SELECT * FROM tb_usuarios a INNER JOIN tb_pessoas b USING(idpessoa) ORDER BY b.despessoa");
		}

		public static function getPasswordHash($senha){
			return password_hash($senha, PASSWORD_DEFAULT, [
				"cost"=>12
			]);
		}

		public function save(){
			$sql = new Sql();
			$results = $sql->select("CALL sp_usuarios_save (:despessoa, :deslogin, :dessenha, :desemail, :nrtel, :inadmin)",[

				":despessoa"=>$this->getdespessoa(),
				":deslogin"=>$this->getdeslogin(),
				":dessenha"=>Usuario::getPasswordHash($this->getdessenha()),
				":desemail"=>$this->getdesemail(),
				":nrtel"=>$this->getnrtel(),
				":inadmin"=>$this->getinadmin()
			]);
			$this->setData($results[0]);
		}

		public function get($idusuario){

			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_usuarios a INNER JOIN tb_pessoas b USING(idpessoa) WHERE a.idusuario = :idusuario", [
				":idusuario"=>$idusuario
			]);
			$this->setData($results[0]);
		}

		public function update(){

			$sql = new Sql();
			$results = $sql->select("CALL sp_usuariosupdate_save(:idusuario, :despessoa, :deslogin, :dessenha, :desemail, :nrtel, :inadmin)",[
				":idusuario"=>$this->getidusuario(),
				":despessoa"=>$this->getdespessoa(),
				":deslogin"=>$this->getdeslogin(),
				":dessenha"=>$this->getdessenha(),
				":desemail"=>$this->getdesemail(),
				":nrtel"=>$this->getnrtel(),
				":inadmin"=>$this->getinadmin()
			]);
			$this->setData($results[0]);
		}

		public function delete(){
			$sql = new Sql();
			$sql->select("CALL sp_usuarios_delete(:idusuario)",[
				":idusuario"=>$this->getidusuario()
			]);
		}

		public static function getForgot($email, $inadmin = true){
			$sql = new Sql();
			$results = $sql->select("SELECT * FROM tb_pessoas a INNER JOIN tb_usuarios b USING(idpessoa) WHERE a.desemail = :email",[
				":email"=>$email
			]);
			if(count($results) === 0){
				throw new \Exception("Email não encontrado.");
			}else{
				$data = $results[0];
				$resultsRecovery = $sql->select("CALL sp_recupsenhausuarios_create(:idusuario, :desip)",[
					":idusuario"=>$data['idusuario'],
					":desip"=>$_SERVER['REMOTE_ADDR']
				]);
				if(count($resultsRecovery) === 0){
					throw new Exception("Não foi possivel recuperar a senha");
				}else{
					$dataRecovery = $resultsRecovery[0];
					$code = openssl_encrypt($dataRecovery['idrecuperacao'], 'AES-128-CBC', pack("a16", Usuario::SECRET), 0, pack("a16", Usuario::SECRET_IV));
					$code = base64_encode($code);

					$link = "http://www.sapc.com.br/esqueceu/reset?code=$code";
				
					$mailer = new Mailer($data['desemail'], $data['despessoa'], "SAPC - Redefinir Senha","recupsenhaemail",[
						"name"=>$data['despessoa'],
						"link"=>$link
					]);

					$mailer->send();
					return $link;
				}
			}
		}

		public static function validForgotDecrypt($code){
			$code = base64_decode($code);
			$idrecuperacao = openssl_decrypt($code, 'AES-128-CBC', pack("a16", Usuario::SECRET),0,pack("a16", Usuario::SECRET_IV));
			$sql = new Sql();
			$results = $sql->select("SELECT * FROM tb_recupsenhausuarios a INNER JOIN tb_usuarios b USING(idusuario) INNER JOIN tb_pessoas c USING(idpessoa) WHERE a.idrecuperacao = :idrecuperacao AND a.dtrecup IS NOT NULL AND DATE_ADD(a.dtregistro, INTERVAL 1 HOUR) >= NOW();", [
				":idrecuperacao"=>$idrecuperacao
			]);
			
			if(count($results) === 0){
				throw new \Exception("Não foi possível recuperar a senha");
			}else{
				return $results[0];
			}
		}

		public static function setForgotUsed($idrecuperacao){
			$sql = new Sql();
			$sql->query("UPDATE tb_recupsenhausuarios SET dtregistro = NOW() WHERE idrecuperacao = :idrecuperacao", [
				":idrecuperacao"=>$idrecuperacao
			]);
		}

		public function setSenha($senha){
			$sql = new Sql();
			$sql->query("UPDATE tb_usuarios SET dessenha = :senha WHERE idusuario = :idusuario",[
				":senha"=>$senha,
				":idusuario"=>$this->getidusuario()
			]);
		}

		public static function getFromSession(){

		$usuario = new Usuario();

		if (isset($_SESSION[Usuario::SESSION]) && (int)$_SESSION[Usuario::SESSION]['idusuario'] > 0) {

			$usuario->setData($_SESSION[Usuario::SESSION]);

		}

		return $usuario;
	}

	public static function checkLogin($inadmin = true){

		if (
			!isset($_SESSION[Usuario::SESSION])
			||
			!$_SESSION[Usuario::SESSION]
			||
			!(int)$_SESSION[Usuario::SESSION]["idusuario"] > 0
		) {
			//Não está logado
			return false;

		} else {

				if ($inadmin === true && (bool)$_SESSION[Usuario::SESSION]['inadmin'] === true) {

					return true;

				} else if ($inadmin === false) {

					return true;

				} else{

					return false;
				}
			}
		}

			public static function checkLoginExist($login)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_usuarios WHERE deslogin = :deslogin", [
			':deslogin'=>$login
		]);

		return (count($results) > 0);

	}

		public static function setError($msg)
	{

		$_SESSION[Usuario::ERROR] = $msg;

	}

	public static function getError()
	{

		$msg = (isset($_SESSION[Usuario::ERROR]) && $_SESSION[Usuario::ERROR]) ? $_SESSION[Usuario::ERROR] : '';

		Usuario::clearError();

		return $msg;

	}

	public static function clearError()
	{

		$_SESSION[Usuario::ERROR] = NULL;

	}

	public static function setSuccess($msg)
	{

		$_SESSION[Usuario::SUCCESS] = $msg;

	}

	public static function getSuccess()
	{

		$msg = (isset($_SESSION[Usuario::SUCCESS]) && $_SESSION[Usuario::SUCCESS]) ? $_SESSION[Usuario::SUCCESS] : '';

		Usuario::clearSuccess();

		return $msg;

	}

	public static function clearSuccess()
	{

		$_SESSION[Usuario::SUCCESS] = NULL;

	}

	public static function setErrorRegister($msg)
	{

		$_SESSION[Usuario::ERROR_REGISTER] = $msg;

	}

	public static function getErrorRegister()
	{

		$msg = (isset($_SESSION[Usuario::ERROR_REGISTER]) && $_SESSION[Usuario::ERROR_REGISTER]) ? $_SESSION[Usuario::ERROR_REGISTER] : '';

		Usuario::clearErrorRegister();

		return $msg;

	}

	public static function clearErrorRegister()
	{

		$_SESSION[Usuario::ERROR_REGISTER] = NULL;

	}
	public static function getPagina($pagina = 1, $itensPorPagina = 10)
	{

		$inicio = ($pagina - 1) * $itensPorPagina;

		$sql = new Sql();

		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_usuarios a 
			INNER JOIN tb_pessoas b USING(idpessoa) 
			ORDER BY b.despessoa
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
			FROM tb_usuarios a 
			INNER JOIN tb_pessoas b USING(idpessoa)
			WHERE b.despessoa LIKE :busca OR b.desemail = :busca OR a.deslogin LIKE :busca
			ORDER BY b.despessoa
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