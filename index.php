<?php 
session_start();

require_once("vendor/autoload.php");
require_once("funcoes.php");

//use TH\DB\Sql;
use \Slim\Slim;
use TH\Page;
use TH\PageAdmin;
use TH\modelos\Usuario;
use TH\modelos\Categoria;
use TH\modelos\Produtos;
use TH\modelos\Morador;
use TH\modelos\Endereco;
use TH\modelos\Visita;
use TH\modelos\StatusVisita;

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function(){
	Usuario::verifyLogin(false);
	$page = new Page();
	$page->setTpl("index");
});

/*----------TESTE DE CONEXÃO----------------------
	$sql = new Sql();
	$results = $sql->select("SELECT * FROM tb_moradores");
    echo json_encode($results);

--------------------------------------------------*/

$app->get('/admin', function() {
    Usuario::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("index");

});

$app->get('/login', function() {
	$page = new Page([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login", [
		"error"=>Usuario::getError(),
		"errorRegister"=>Usuario::getErrorRegister(),
		"registrarValoresLogin"=>(isset($_SESSION["registrarValoresLogin"])) ? $_SESSION['registrarValoresLogin'] : ['login'=>'', 'password'=>'']
	]);
});

$app->post('/login', function(){
	$_SESSION['registrarValoresLogin'] = $_POST;
	try{
	Usuario::Login($_POST['login'], $_POST['password']);
	}catch(Exception $e){
		Usuario::setError($e->getMessage());
		header("Location: /login");
		exit;
	}
	if(isset($_POST['g-recaptcha-response'])){
      $captcha = $_POST['g-recaptcha-response'];
    }
    if(!$captcha){
     	Usuario::setError("Preencha a verificação");
		header("Location: /login");
		exit; 
    }
    $_SESSION['registrarValoresLogin'] = NULL;

	header("Location:/admin");
	exit;
});

$app->get('/logout', function(){
	Usuario::logOut();
	session_regenerate_id();
	header("Location: /login");
	exit;
});

$app->get('/admin/usuarios', function(){
	Usuario::verifyLogin();
	//$usuarios = Usuario::listAll();
	$busca = (isset($_GET['busca'])) ? $_GET['busca'] : "";
	$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	if ($busca != ''){
		$paginacao = Usuario::getPaginaBusca($busca, $pagina, 5);
	}else{
		$paginacao = Usuario::getPagina($pagina);
	}
	$paginas = [];
	for ($i = 0; $i < $paginacao['paginas']; $i++){
		array_push($paginas, [
			'href'=>'/admin/usuarios?'.http_build_query([
				'pagina'=>$i+1,
				'busca'=>$busca
			]),
			'texto'=>$i+1
		]);
	}
	$page = new PageAdmin();
	$page->setTpl("usuarios",[
		"usuarios"=>$paginacao['data'],
		"busca"=>$busca,
		"paginas"=>$paginas
	]);
});

$app->get('/admin/usuarios/cadastrar', function(){
	Usuario::verifyLogin();
	$usuario = new Usuario();
	$page = new PageAdmin();
	$page->setTpl("usuarios-cadastrar",[
	"registrarValoresUsuario"=>(!empty($_SESSION["registrarValoresUsuario"])) ? $_SESSION['registrarValoresUsuario'] : ['despessoa'=>'', 'deslogin'=>'', 'nrtel'=>'', 'desemail'=>'', 'dessenha'=>''],
	"msgError"=>$usuario->getError()
	]);
});

$app->post('/admin/usuarios/cadastrar', function(){
	$_SESSION['registrarValoresUsuario'] = $_POST;
	Usuario::verifyLogin();
	if(!isset($_POST['despessoa']) || $_POST['despessoa'] === ''){
		Usuario::setError("Digite o nome do usuário.");
		header("Location: /admin/usuarios/cadastrar");
		exit;
	}
	if(!isset($_POST['deslogin']) || $_POST['deslogin'] === ''){
		Usuario::setError("Digite o login do usuário.");
		header("Location: /admin/usuarios/cadastrar");
		exit;
	}
	if(!isset($_POST['desemail']) || $_POST['desemail'] === ''){
		Usuario::setError("Digite o email do usuário.");
		header("Location: /admin/usuarios/cadastrar");
		exit;
	}
	if(!isset($_POST['dessenha']) || $_POST['dessenha'] === ''){
		Usuario::setError("Digite a senha do usuário");
		header("Location: /admin/usuarios/cadastrar");
		exit;
	}

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
	$senha = password_hash($_POST["dessenha"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);
	$usuarios = new Usuario();
	if($_POST['deslogin'] !== $usuarios->getdeslogin()){
		if(Usuario::checkLoginExist($_POST['deslogin']) === true){
			Usuario::setError("Este login já está sendo utilizado");
			header("Location: /admin/usuarios/cadastrar");
				exit;
		}
	}
		if($_POST['desemail'] !== $usuarios->getdesemail()){
		if(Usuario::checkLoginExist($_POST['desemail']) === true){
			Usuario::setError("Este endereço de email já foi cadastrado");
			header("Location: /admin/usuarios/cadastrar");
				exit;
		}
	}
	$usuarios->setData($_POST);
	$usuarios->save();
	$_SESSION['registrarValoresUsuario'] = NULL;
	header("Location: /admin/usuarios");
	exit;

});

$app->get('/admin/usuarios/:idusuario/delete', function($idusuario){
	Usuario::verifyLogin();
	$usuarios = new Usuario();
	$usuarios->get((int)$idusuario);
	$usuarios->delete();
	header("Location: /admin/usuarios");
	exit;

});

$app->get('/admin/usuarios/:idusuario', function($idusuario){
	Usuario::verifyLogin();
	$usuario = new Usuario();
	$usuario->get((int)$idusuario);
	$page = new PageAdmin();
	$page->setTpl("usuarios-atualizar", [
		"usuarios"=>$usuario->getValues(),
		"msgError"=>$usuario->getError(),
		"msgSuccess"=>Usuario::getSuccess()
	]);

});

$app->post('/admin/usuarios/:idusuario', function($idusuario){
	Usuario::verifyLogin();
	$usuario = new Usuario();
	$usuario->get((int)$idusuario);
		if(!isset($_POST['despessoa']) || $_POST['despessoa'] === ''){
		Usuario::setError("Digite o nome do usuário.");
		header("Location: /admin/usuarios/".$usuario->getidusuario());
		exit;
	}
	if(!isset($_POST['deslogin']) || $_POST['deslogin'] === ''){
		Usuario::setError("Digite o login do usuário.");
		header("Location: /admin/usuarios/".$usuario->getidusuario());
		exit;
	}
	if(!isset($_POST['desemail']) || $_POST['desemail'] === ''){
		Usuario::setError("Digite o email do usuário.");
		header("Location: /admin/usuarios/".$usuario->getidusuario());
		exit;
	}
	if(!isset($_POST['nrtel']) || $_POST['nrtel'] === ''){
		Usuario::setError("Digite o número do telefone do usuário.");
		header("Location: /admin/usuarios/".$usuario->getidusuario());
		exit;
	}
	if($_POST['deslogin'] !== $usuario->getdeslogin()){
		if(Usuario::checkLoginExist($_POST['deslogin']) === true){
			Usuario::setError("Este login já está sendo utilizado");
			header("Location: /admin/usuarios/".$usuario->getidusuario());
				exit;
		}
	}
	if($_POST['desemail'] !== $usuario->getdesemail()){
		if(Usuario::checkLoginExist($_POST['desemail']) === true){
			Usuario::setError("Este endereço de email já esta sendo utilizado.");
			header("Location: /admin/usuarios/".$usuario->getidusuario());
				exit;
		}
	}

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
	$usuario->setData($_POST);
	$usuario->update();
	Usuario::setSuccess("Dados alterados com sucesso!");
	header("Location: /admin/usuarios/".$usuario->getidusuario());
	exit;
});

$app->get("/admin/editar/senha", function(){
	Usuario::verifyLogin();
	$usuario = new Usuario();
	$page = new PageAdmin();
	$page->setTpl("usuarios-atualizar-senha",[
		"usuario"=>$usuario->getValues(),
		"msgError"=>$usuario->getError(),
		"msgSuccess"=>$usuario->getSuccess()
	]);
});

$app->post("/admin/editar/senha", function(){
	Usuario::verifyLogin();
	if(!isset($_POST['dessenha']) || $_POST['dessenha'] === ''){
		Usuario::setError('Informe a nova senha.');
		header("Location: /admin/editar/senha");
		exit;
	}
	if(!isset($_POST['dessenha-confirmar']) || $_POST['dessenha-confirmar'] === ''){
	Usuario::setError('Informe a confirmação da nova senha.');
	header("Location: /admin/editar/senha");
	exit;
	}
	$usuario = Usuario::getFromSession();
	if($_POST['dessenha'] !== $_POST['dessenha-confirmar']){
		Usuario::setError('Confirme corretamente as senhas.');
		header("Location: /admin/editar/senha");
		exit;
	}

	$usuario->setdessenha(Usuario::getPasswordHash($_POST['dessenha']));
	$usuario->update();
	Usuario::setSuccess('Senha alterada com sucesso!');
	header("Location: /admin/editar/senha");
	exit;
});

$app->get("/admin/editar", function(){
	Usuario::verifyLogin();
	$usuario = Usuario::getFromSession();
	$page = new PageAdmin();
	$page->setTpl("usuarios-editar",[
		"usuario"=>$usuario->getValues(),
		"msgError"=>Usuario::getError(),
		"msgSuccess"=>Usuario::getSuccess()
	]);
});

$app->post("/admin/editar", function(){
	Usuario::verifyLogin();
	if(!isset($_POST['despessoa']) || $_POST['despessoa'] === ''){
		Usuario::setError('Informe seu novo nome.');
		header('Location: /admin/editar');
		exit;
	}
	if(!isset($_POST['deslogin']) || $_POST['deslogin'] === ''){
		Usuario::setError('Informe seu novo login.');
		header('Location: /admin/editar');
		exit;
	}
	if(!isset($_POST['nrtel']) || $_POST['nrtel'] === ''){
		Usuario::setError('Informe seu novo telefone.');
		header('Location: /admin/editar');
		exit;
	}
	if(!isset($_POST['desemail']) || $_POST['desemail'] === ''){
		Usuario::setError('Informe seu novo email.');
		header('Location: /admin/editar');
		exit;
	}
	$usuario = Usuario::getFromSession();
	if($_POST['deslogin'] !== $usuario->getdeslogin()){
			if(Usuario::checkLoginExist($_POST['deslogin']) === true){
				Usuario::setError("Este login já esta sendo utilizado");
				header("Location: /admin/editar");
				exit;
			}
	}
	$_POST['inadmin'] = $usuario->getinadmin();
	$_POST['despassword'] = $usuario->getdessenha();
	$usuario->setData($_POST);
	$usuario->update();
	$_SESSION[Usuario::SESSION] = $usuario->getValues();
	Usuario::setSuccess("Dados alterados com sucesso!");
	header("Location: /admin/editar");
	exit;
});

$app->get('/esqueceu', function(){
	$page = new Page([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("esqueceu", 
	[
		"error"=>Usuario::getError()
	]);
});

$app->post('/esqueceu', function(){
	try{
	$usuario = Usuario::getForgot($_POST["email"]);
	}catch(Exception $e){
		Usuario::setError($e->getMessage());
		header("Location: /esqueceu");
		exit;
	}
	header("Location: /esqueceu/enviar");
	exit;
});

$app->get('/esqueceu/enviar', function(){
	$page = new Page([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("esqueceu-enviado");
});

$app->get('/esqueceu/reset', function(){
	$usuario = Usuario::validForgotDecrypt($_GET["code"]);
	$page = new Page([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("esqueceu-reset",[
		"name"=>$usuario["despessoa"],
		"code"=>$_GET["code"]
	]);
});

$app->post('/esqueceu/reset', function(){
	$esqueceu = Usuario::validForgotDecrypt($_POST["code"]);
	Usuario::setForgotUsed($esqueceu["idrecuperacao"]);
	$usuario = new Usuario;
	$usuario->get((int)$esqueceu["idusuario"]);
	$senha = password_hash($_POST["dessenha"], PASSWORD_DEFAULT,[
		"cost"=>12
	]);
	$usuario->setSenha($senha);
	$page = new Page([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("esqueceu-reset-sucesso");

});

$app->get('/admin/categorias', function(){
	Usuario::verifyLogin();
	//$categorias = Categoria::listAll();
	$busca = (isset($_GET['busca'])) ? $_GET['busca'] : "";
	$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	if ($busca != ''){
		$paginacao = Categoria::getPaginaBusca($busca, $pagina, 5);
	}else{
		$paginacao = Categoria::getPagina($pagina);
	}
	$paginas = [];
	for ($i = 0; $i < $paginacao['paginas']; $i++){
		array_push($paginas, [
			'href'=>'/admin/categorias?'.http_build_query([
				'pagina'=>$i+1,
				'busca'=>$busca
			]),
			'texto'=>$i+1
		]);
	}
	$page = new PageAdmin();
	$page->setTpl("categorias",[
		"categorias"=>$paginacao['data'],
		"busca"=>$busca,
		"paginas"=>$paginas
	]);
});

$app->get('/admin/categorias/cadastrar', function(){
	Usuario::verifyLogin();
	$usuario = new Usuario();
	$page = new PageAdmin();
	$page->setTpl("categorias-cadastrar", [
	"msgError"=>$usuario->getError(),
	"registrarValoresCategorias"=>(!empty($_SESSION["registrarValoresCategorias"])) ? $_SESSION['registrarValoresCategorias'] : ['descategoria'=>'']
	]);
});

$app->post('/admin/categorias/cadastrar', function(){
	Usuario::verifyLogin();
	$_SESSION["registrarValoresCategorias"] = $_POST;
	$categoria = new Categoria();
	if(!isset($_POST['descategoria']) || $_POST['descategoria'] === ''){
		Usuario::setError("Informe a categoria");
		header("Location: /admin/categorias/cadastrar");
		exit;
	}
	if(Categoria::checkCategoriaExist($_POST['descategoria']) === true){
		Usuario::setError("Já existe uma categoria cadastrada com esse nome");
		header("Location: /admin/categorias/cadastrar");
		exit;
	}
	$categoria->setData($_POST);
	$categoria->save();
	$_SESSION["registrarValoresCategorias"] = NULL;
	header("Location: /admin/categorias");
	exit;
});

$app->get('/admin/categorias/:idcategoria/delete', function($idcategoria){
	Usuario::verifyLogin();
	$categoria = new Categoria();
	$categoria->get((int)$idcategoria);
	$categoria->delete();
	header("Location: /admin/categorias");
	exit;
});

$app->get('/admin/categorias/:idcategoria', function($idcategoria){
	Usuario::verifyLogin();
	$usuario = new Usuario();
	$categoria = new Categoria();
	$categoria->get((int)$idcategoria);
	$page = new PageAdmin();
	$page->setTpl("categorias-atualizar",[
		"categoria"=>$categoria->getValues(),
		"produtos"=>$categoria->getProdutos(),
		"msgError"=>$usuario->getError()
	]);
});

$app->post('/admin/categorias/:idcategoria', function($idcategoria){
	Usuario::verifyLogin();
	$categoria = new Categoria();
	$categoria->get((int)$idcategoria);
	if(!isset($_POST['descategoria']) || $_POST['descategoria'] === ''){
		Usuario::setError("Informe a categoria");
		header("Location: /admin/categorias/".$idcategoria);
		exit;
	}
	if(Categoria::checkCategoriaExist($_POST['descategoria']) === true){
		Usuario::setError("Já existe uma categoria cadastrada com esse nome");
		header("Location: /admin/categorias/cadastrar");
		exit;
	}
	$categoria->setData($_POST);
	$categoria->save();
	header("Location: /admin/categorias");
	exit();
});

$app->get('/admin/produtos', function(){
	Usuario::verifyLogin();
	//$produtos = Produtos::listAll();
	$busca = (isset($_GET['busca'])) ? $_GET['busca'] : "";
	$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	if ($busca != ''){
		$paginacao = Produtos::getPaginaBusca($busca, $pagina, 5);
	}else{
		$paginacao = Produtos::getPagina($pagina);
	}
	$paginas = [];
	for ($i = 0; $i < $paginacao['paginas']; $i++){
		array_push($paginas, [
			'href'=>'/admin/produtos?'.http_build_query([
				'pagina'=>$i+1,
				'busca'=>$busca
			]),
			'texto'=>$i+1
		]);
	}
	$page = new PageAdmin();
	$page->setTpl("produtos",[
		"produtos"=>$paginacao['data'],
		"busca"=>$busca,
		"paginas"=>$paginas
	]);
});

$app->get('/admin/produtos/cadastrar', function(){
	Usuario::verifyLogin();
	$usuario = new Usuario();
	$page = new PageAdmin();
	$page->setTpl("produtos-cadastrar", [
		"registrarValoresProdutos"=>(!empty($_SESSION["registrarValoresProdutos"])) ? $_SESSION['registrarValoresProdutos'] : ['desproduto'=>'','desdoador'=>''],
		'msgError'=>$usuario->getError(),
		'categorias'=>Categoria::listAll()
	]);
});

$app->post('/admin/produtos/cadastrar', function(){
	Usuario::verifyLogin();
	$_SESSION['registrarValoresProdutos'] = $_POST;
	$produtos = new Produtos();
	if(!isset($_POST['desproduto']) || $_POST['desproduto'] === ''){
		Usuario::setError("Informe o nome do produto.");
		header("Location: /admin/produtos/cadastrar");
		exit;
	}
	if(Produtos::checkProdutoExist($_POST['desproduto']) === true){
		Usuario::setError("Já existe um produto cadastrado com esse nome");
		header("Location: /admin/produtos/cadastrar");
		exit;
	}
	$produtos->setData($_POST);
	$_SESSION['registrarValoresProdutos'] = NULL;
	$produtos->save();
	header("Location: /admin/produtos");
	exit;
});

$app->get('/admin/produtos/:idproduto', function($idproduto){
	Usuario::verifyLogin();
	$usuario = new Usuario();
	$produto = new Produtos();
	$produto->get((int)$idproduto);
	$page = new PageAdmin();
	$page->setTpl("produtos-atualizar",[
		"produto"=>$produto->getValues(),
		"msgError"=>$usuario->getError(),
		"registrarValoresProdutos"=>(!empty($_SESSION["registrarValoresProdutos"])) ? $_SESSION['registrarValoresProdutos'] : ['desproduto'=>'','desdoador'=>'']
	]);
});

$app->post("/admin/produtos/:idproduto", function($idproduto){
	Usuario::verifyLogin();
	$_SESSION['registrarValoresProdutos'] = $_POST;
	$produto = new Produtos();
	$produto->get((int)$idproduto);
	if(!isset($_POST['desproduto']) || $_POST['desproduto'] === ''){
		Usuario::setError("Informe o nome do produto.");
		header("Location: /admin/produtos/".$idproduto);
		exit;
	}
	if(Produtos::checkProdutoExist($_POST['desproduto']) === true){
		Usuario::setError("Já existe um produto cadastrado com esse nome");
		header("Location: /admin/produtos/cadastrar");
		exit;
	}
	$produto->setData($_POST);
	$_SESSION['registrarValoresProdutos'] = NULL;
	$produto->save();
	header("Location: /admin/produtos");
	exit;
});

$app->get("/admin/produtos/:idproduto/delete", function($idproduto){
	Usuario::verifyLogin();
	$produto = new Produtos();
	$produto->get((int)$idproduto);
	$produto->delete();
	header("Location: /admin/produtos");
	exit;
});

$app->get('/admin/categorias/:idcategoria/produtos', function($idcategoria){
	Usuario::verifyLogin();
	$categoria = new Categoria();
	$categoria->get((int)$idcategoria);
	$page = new PageAdmin();
	$page->setTpl("categorias-produtos", [
		"categoria"=>$categoria->getValues(),
		"produtosRelacionados"=>$categoria->getProdutos(),
		"produtosNaoRelacionados"=>$categoria->getProdutos(false)
	]);
});

$app->get('/admin/categorias/:idcategoria/produtos/:idproduto/add',function($idcategoria, $idproduto){
	Usuario::verifyLogin();
	$categoria = new Categoria();
	$categoria->get((int)$idcategoria);
	$produto = new Produtos();
	$produto->get((int)$idproduto);
	$categoria->addProduto($produto);
	header("Location: /admin/categorias/".$idcategoria."/produtos");
	exit;
});

$app->get('/admin/categorias/:idcategoria/produtos/:idproduto/remove', function($idcategoria, $idproduto){
	Usuario::verifyLogin();
	$categoria = new Categoria();
	$categoria->get((int)$idcategoria);
	$produto = new Produtos();
	$produto->get((int)$idproduto);
	$categoria->removeProduto($produto);
	header("Location: /admin/categorias/".$idcategoria."/produtos");
	exit;
});

$app->get("/admin/moradores", function(){
	Usuario::verifyLogin();
	//$moradores = Morador::listAll();
	$busca = (isset($_GET['busca'])) ? $_GET['busca'] : "";
	$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	if ($busca != ''){
		$paginacao = Morador::getPaginaBusca($busca, $pagina, 5);
	}else{
		$paginacao = Morador::getPagina($pagina);
	}
	$paginas = [];
	for ($i = 0; $i < $paginacao['paginas']; $i++){
		array_push($paginas, [
			'href'=>'/admin/moradores?'.http_build_query([
				'pagina'=>$i+1,
				'busca'=>$busca
			]),
			'texto'=>$i+1
		]);
	}
	$page = new PageAdmin();
	$page->setTpl("moradores", [
		"moradores"=>$paginacao['data'],
		"busca"=>$busca,
		"paginas"=>$paginas
	]);
});

$app->get("/admin/moradores/cadastrar", function(){
	Usuario::verifyLogin();
	$_SESSION['registrarValoresMorador'] = $_GET;
	$usuario = new Usuario();
	$morador = new Morador();
	if (isset($_GET['descep'])){
	$morador->loadFromCEP($_GET['descep']);
	}
	if(!$morador->getdesrua()) $morador->setdesrua('');
	if(!$morador->getdesnumero()) $morador->setdesnumero('');
	if(!$morador->getdescomplemento()) $morador->setdescomplemento('');
	if(!$morador->getdesbairro()) $morador->setdesbairro('');
	if(!$morador->getdescidade()) $morador->setdescidade('');
	if(!$morador->getdesestado()) $morador->setdesestado('');
	if(!$morador->getdespais()) $morador->setdespais('');
	if(!$morador->getdescep()) $morador->setdescep('');;
	$page = new PageAdmin();
	$page->setTpl("moradores-cadastrar",[
		"registrarValoresMorador"=>(!empty($_SESSION["registrarValoresMorador"])) ? $_SESSION["registrarValoresMorador"] : ['desmorador'=>'', 'desidentidade'=>'', 'descpf'=>'', 'nrtel'=>'', 'desemail'=>'', 'nrpessoas'=>''],
		"msgError"=>$usuario->getError(),
		"morador"=>$morador->getValues()
	]);
});

$app->post("/admin/moradores/cadastrar", function(){
	Usuario::verifyLogin();
	$_SESSION['registrarValoresMorador'] = $_GET;
	$usuario = new Usuario();
	$morador = new Morador();
	$morador->setData($_POST);
	$morador->save();
	$_SESSION['registrarValoresMorador'] = NULL;
	header("Location: /admin/moradores");
	exit;


});

$app->get("/admin/moradores/:idmorador/delete", function($idmorador){
	Usuario::verifyLogin();
	$morador = new Morador();
	$morador->get((int)$idmorador);
	$morador->delete();
	header("Location: /admin/moradores");
	exit;
});

$app->get("/admin/moradores/:idmorador", function($idmorador){
	Usuario::verifyLogin();
	$morador = new Morador();
	$morador->get((int)$idmorador);
	if (isset($_GET['descep'])){
	$morador->loadFromCEP($_GET['descep']);
	}
	if(!$morador->getdesrua()) $morador->setdesrua('');
	if(!$morador->getdesnumero()) $morador->setdesnumero('');
	if(!$morador->getdescomplemento()) $morador->setdescomplemento('');
	if(!$morador->getdesbairro()) $morador->setdesbairro('');
	if(!$morador->getdescidade()) $morador->setdescidade('');
	if(!$morador->getdesestado()) $morador->setdesestado('');
	if(!$morador->getdespais()) $morador->setdespais('');
	if(!$morador->getdescep()) $morador->setdescep('');
	$page = new PageAdmin();
	$page->setTpl("moradores-atualizar", [
		"morador"=>$morador->getValues(),
		"registrarValoresMorador"=>(!empty($_SESSION["registrarValoresMorador"])) ? $_SESSION["registrarValoresMorador"] : ['desmorador'=>'', 'desidentidade'=>'', 'descpf'=>'', 'nrtel'=>'', 'desemail'=>'', 'nrpessoas'=>'', 'desnumero'=>'', 'descomplemento'=>'']

	]);	
});

$app->post("/admin/moradores/:idmorador", function($idmorador){
	Usuario::verifyLogin();
	$_SESSION['registrarValoresMorador'] = $_POST;
	$morador = new Morador();
	$morador->get((int)$idmorador);
	$morador->setData($_POST);
	$morador->update();
	$_SESSION["registrarValoresMorador"] = NULL;
	header("Location: /admin/moradores");
	exit;

});

$app->get("/moradores", function(){
	Usuario::verifyLogin(false);
	//$moradores = Morador::listAll();
	$busca = (isset($_GET['busca'])) ? $_GET['busca'] : "";
	$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	if ($busca != ''){
		$paginacao = Morador::getPaginaBusca($busca, $pagina, 5);
	}else{
		$paginacao = Morador::getPagina($pagina);
	}
	$paginas = [];
	for ($i = 0; $i < $paginacao['paginas']; $i++){
		array_push($paginas, [
			'href'=>'/moradores?'.http_build_query([
				'pagina'=>$i+1,
				'busca'=>$busca
			]),
			'texto'=>$i+1
		]);
	}
	$page = new Page();
	$page->setTpl("moradores", [
		"moradores"=>$paginacao['data'],
		"busca"=>$busca,
		"paginas"=>$paginas
	]);
});

$app->get("/moradores/cadastrar", function(){
	Usuario::verifyLogin(false);
	$_SESSION['registrarValoresMorador'] = $_GET;
	$usuario = new Usuario();
	$morador = new Morador();
	if (isset($_GET['descep'])){
	$morador->loadFromCEP($_GET['descep']);
	}
	if(!$morador->getdesrua()) $morador->setdesrua('');
	if(!$morador->getdesnumero()) $morador->setdesnumero('');
	if(!$morador->getdescomplemento()) $morador->setdescomplemento('');
	if(!$morador->getdesbairro()) $morador->setdesbairro('');
	if(!$morador->getdescidade()) $morador->setdescidade('');
	if(!$morador->getdesestado()) $morador->setdesestado('');
	if(!$morador->getdespais()) $morador->setdespais('');
	if(!$morador->getdescep()) $morador->setdescep('');;
	$page = new Page();
	$page->setTpl("moradores-cadastrar",[
		"registrarValoresMorador"=>(!empty($_SESSION["registrarValoresMorador"])) ? $_SESSION["registrarValoresMorador"] : ['desmorador'=>'', 'desidentidade'=>'', 'descpf'=>'', 'nrtel'=>'', 'desemail'=>'','nrpessoas'=>'', 'desrua'=>'', 'descomplemento'=>''],
		"msgError"=>$usuario->getError(),
		"morador"=>$morador->getValues()
	]);
});

$app->post("/moradores/cadastrar", function(){
	Usuario::verifyLogin(false);
	$_SESSION['registrarValoresMorador'] = $_GET;
	$usuario = new Usuario();
	$morador = new Morador();
	$_SESSION['registrarValoresMorador'] = NULL;
	$morador->setData($_POST);
	$morador->save();
	header("Location: /moradores");
	exit;
});

$app->get("/moradores/:idmorador/delete", function($idmorador){
	Usuario::verifyLogin(false);
	$morador = new Morador();
	$morador->get((int)$idmorador);
	$morador->delete();
	header("Location: /moradores");
	exit;
});

$app->get("/moradores/:idmorador", function($idmorador){
	Usuario::verifyLogin(false);
	$morador = new Morador();
	$_SESSION['registrarValoresMorador'] = $_POST;
	$morador->get((int)$idmorador);
	if (isset($_GET['descep'])){
	$morador->loadFromCEP($_GET['descep']);
	}
	if(!$morador->getdesrua()) $morador->setdesrua('');
	if(!$morador->getdesnumero()) $morador->setdesnumero('');
	if(!$morador->getdescomplemento()) $morador->setdescomplemento('');
	if(!$morador->getdesbairro()) $morador->setdesbairro('');
	if(!$morador->getdescidade()) $morador->setdescidade('');
	if(!$morador->getdesestado()) $morador->setdesestado('');
	if(!$morador->getdespais()) $morador->setdespais('');
	if(!$morador->getdescep()) $morador->setdescep('');
	$page = new Page();
	$page->setTpl("moradores-atualizar", [
		"morador"=>$morador->getValues(),
		"registrarValoresMorador"=>(!empty($_SESSION["registrarValoresMorador"])) ? $_SESSION["registrarValoresMorador"] : ['desmorador'=>'', 'desidentidade'=>'', 'descpf'=>'', 'nrtel'=>'', 'desemail'=>'', 'nrpessoas'=>'', 'desnumero'=>'', 'descomplemento'=>'']
	]);	
});

$app->post("/moradores/:idmorador", function($idmorador){
	Usuario::verifyLogin(false);
	$morador = new Morador();
	$morador->get((int)$idmorador);
	$morador->setData($_POST);
	$morador->update();
	header("Location: /moradores");
	exit;
	$_SESSION["registrarValoresMorador"] = NULL;
});

$app->get("/admin/visitas", function(){
	Usuario::verifyLogin();
	$morador = new Morador();
	$visita = new Visita();
	$busca = (isset($_GET['busca'])) ? $_GET['busca'] : "";
	$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	if ($busca != ''){
		$paginacao = Visita::getPaginaBusca($busca, $pagina, 5);
	}else{
		$paginacao = Visita::getPagina($pagina);
	}
	$paginas = [];
	for ($i = 0; $i < $paginacao['paginas']; $i++){
		array_push($paginas, [
			'href'=>'/admin/visitas?'.http_build_query([
				'pagina'=>$i+1,
				'busca'=>$busca
			]),
			'texto'=>$i+1
		]);
	}
	$page = new PageAdmin();
	$page->setTpl("visitas", [
		"visitas"=>$paginacao['data'],
		"busca"=>$busca,
		"paginas"=>$paginas,
		"morador"=>$morador->getValues()
	]);
});

$app->get("/admin/visitas/agendar", function(){
	Usuario::verifyLogin();
	//$moradores = Morador::listAll();
	$busca = (isset($_GET['busca'])) ? $_GET['busca'] : "";
	$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	if ($busca != ''){
		$paginacao = Morador::getPaginaBusca($busca, $pagina, 5);
	}else{
		$paginacao = Morador::getPagina($pagina);
	}
	$paginas = [];
	for ($i = 0; $i < $paginacao['paginas']; $i++){
		array_push($paginas, [
			'href'=>'/admin/visitas/agendar?'.http_build_query([
				'pagina'=>$i+1,
				'busca'=>$busca
			]),
			'texto'=>$i+1
		]);
	}
	$page = new PageAdmin();
	$page->setTpl("moradores-visitas", [
		"moradores"=>$paginacao['data'],
		"busca"=>$busca,
		"paginas"=>$paginas
	]);
});

$app->get("/admin/visitas/:idmorador/agendar", function($idmorador){
	Usuario::verifyLogin();
	$morador = new Morador();
	$visita = new Visita();
	$morador->get((int)$idmorador);
	$page = new PageAdmin();
	$page->setTpl("visitas-cadastrar", [
		"moradores"=>Morador::listAll(),
		"produtos"=>Produtos::listAll(),
		"visitas"=>Visita::listAll(),
		"morador"=>$morador->getValues(),
		"msgError"=>Visita::getError(),
		"registrarValoresVisitas"=>(!empty($_SESSION["registrarValoresVisitas"])) ? $_SESSION["registrarValoresVisitas"] : ['desdoacao'=>'', 'dtvisita'=>'', 'deshorario'=>'']
	]);
});

$app->post("/admin/visitas/:idmorador/agendar", function($idmorador){
	Usuario::verifyLogin();
	$_SESSION['registrarValoresVisitas'] = $_POST;
	$visita = new Visita();
	$morador = new Morador();
	$morador->get((int)$idmorador);
	if(!isset($_POST['desdoacao']) || $_POST['desdoacao'] === ''){
		Visita::setError("Selecione o tipo da doação.");
		header("Location: /admin/visitas/".$idmorador."/agendar");
		exit;
	}
	if(!isset($_POST['dtvisita']) || $_POST['dtvisita'] === ''){
		Visita::setError("Informe a data de agendamento.");
		header("Location: /admin/visitas/".$idmorador."/agendar");
		exit;
	}
	if(!isset($_POST['deshorario']) || $_POST['deshorario'] === ''){
		Visita::setError("Selecione o horário.");
		header("Location: /admin/visitas/".$idmorador."/agendar");
		exit;
	}
	$visita->setidstatus(StatusVisita::EM_ABERTO);
	$_POST['dtvisita'] = implode("-", array_reverse(explode("/", $_POST['dtvisita'])));
	$visita->setData($_POST);
	$visita->save();
	if(!empty($_POST['desemailvisita'])){
	$email = Visita::getEmail($_POST["desemailvisita"]);
	}
	$_SESSION['registrarValoresVisitas'] = NULL;
	header("Location: /admin/visitas");
	exit;
});

$app->get("/admin/visitas/:idvisita/delete", function($idvisita){
	Usuario::verifyLogin();
	$visita = new Visita();
	$visita->get((int)$idvisita);
	$visita->delete();
	header("Location: /admin/visitas");
	exit;
});

$app->get("/admin/visitas/:idvisita/status", function($idvisita){
	Usuario::verifyLogin();
	$visita = new Visita();
	$visita->get((int)$idvisita);
	$page = new PageAdmin();
	$page->setTpl("visitas-status", [
		"visita"=>$visita->getValues(),
		"statusvisita"=>StatusVisita::listAll(),
		'msgError'=>Visita::getError(),
		'msgSuccess'=>Visita::getSuccess()
	]);
});

$app->get("/admin/moradores/:idmorador/detalhes", function($idmorador){
	Usuario::verifyLogin();
	$morador = new Morador();
	$morador->get((int)$idmorador);
	$page = new PageAdmin();
	$page->setTpl("moradores-detalhes", [
		"morador"=>$morador->getValues()
	]);
});


$app->post("/admin/visitas/:idvisita/status", function($idvisita){
	Usuario::verifyLogin();
	if(!isset($_POST['idstatus']) || !(int)$_POST['idstatus'] > 0){
		Visita::setError("Informe o status atual");
		header("Location: /admin/visitas/".$idvisita."/status");
		exit;
		}
	if((int)$_POST['idstatus'] == 1){
		Visita::setError("Não é possível mudar o status de uma visita já atendida/cancelada.");
		header("Location: /admin/visitas/".$idvisita."/status");
		exit;
	}
		$visita = new Visita();
		$visita->get((int)$idvisita);
		$visita->setidstatus($_POST['idstatus']);
		$visita->save();
		$visita->setSuccess("Status atualizado com sucesso!");
		header("Location: /admin/visitas/".$idvisita."/status");
		exit;
});

$app->get("/admin/visitas/:idvisita/detalhes", function($idvisita){
	Usuario::verifyLogin();
	$visita = new Visita();
	$visita->get((int)$idvisita);
	$page = new PageAdmin();
	$page->setTpl("visita-detalhes", [
		"visita"=>$visita->getValues(),
		"visitas"=>Visita::listAll()
	]);
});

$app->get("/editar", function(){
	Usuario::verifyLogin(false);
	$usuario = Usuario::getFromSession();
	$page = new Page();
	$page->setTpl("usuarios-editar",[
		"usuario"=>$usuario->getValues(),
		"msgError"=>Usuario::getError(),
		"msgSuccess"=>Usuario::getSuccess()
	]);
});

$app->post("/editar", function(){
	Usuario::verifyLogin(false);
	if(!isset($_POST['despessoa']) || $_POST['despessoa'] === ''){
		Usuario::setError('Informe seu novo nome.');
		header('Location: /editar');
		exit;
	}
	if(!isset($_POST['deslogin']) || $_POST['deslogin'] === ''){
		Usuario::setError('Informe seu novo login.');
		header('Location: /editar');
		exit;
	}
	if(!isset($_POST['nrtel']) || $_POST['nrtel'] === ''){
		Usuario::setError('Informe seu novo telefone.');
		header('Location: /editar');
		exit;
	}
	if(!isset($_POST['desemail']) || $_POST['desemail'] === ''){
		Usuario::setError('Informe seu novo email.');
		header('Location: /editar');
		exit;
	}

	$usuario = Usuario::getFromSession();

	if($_POST['deslogin'] !== $usuario->getdeslogin()){
			if(Usuario::checkLoginExist($_POST['deslogin']) === true){
				Usuario::setError("Este login já esta sendo utilizado");
				header("Location: /editar");
				exit;
			}
	}
	$_POST['inadmin'] = $usuario->getinadmin();
	$_POST['despassword'] = $usuario->getdessenha();
	$usuario->setData($_POST);
	$usuario->update();
	$_SESSION[Usuario::SESSION] = $usuario->getValues();
	Usuario::setSuccess("Dados alterados com sucesso!");
	header("Location: /editar");
	exit;
});

$app->get("/editar/senha", function(){
	Usuario::verifyLogin(false);
	$usuario = new Usuario();
	$page = new Page();
	$page->setTpl("usuarios-atualizar-senha",[
		"usuario"=>$usuario->getValues(),
		"msgError"=>$usuario->getError(),
		"msgSuccess"=>$usuario->getSuccess()
	]);
});

$app->post("/editar/senha", function(){
	Usuario::verifyLogin(false);
	if(!isset($_POST['dessenha']) || $_POST['dessenha'] === ''){
		Usuario::setError('Informe a nova senha.');
		header("Location: /editar/senha");
		exit;
	}
	if(!isset($_POST['dessenha-confirmar']) || $_POST['dessenha-confirmar'] === ''){
	Usuario::setError('Informe a confirmação da nova senha.');
	header("Location: /editar/senha");
	exit;
	}
	$usuario = Usuario::getFromSession();
	if($_POST['dessenha'] !== $_POST['dessenha-confirmar']){
		Usuario::setError('Confirme corretamente as senhas.');
		header("Location: /editar/senha");
		exit;
	}

	$usuario->setdessenha(Usuario::getPasswordHash($_POST['dessenha']));
	$usuario->update();
	Usuario::setSuccess('Senha alterada com sucesso!');
	header("Location: /editar/senha");
	exit;
});

$app->get("/visitas", function(){
	Usuario::verifyLogin(false);
	$morador = new Morador();
	$visita = new Visita();
	$busca = (isset($_GET['busca'])) ? $_GET['busca'] : "";
	$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	if ($busca != ''){
		$paginacao = Visita::getPaginaBusca($busca, $pagina, 5);
	}else{
		$paginacao = Visita::getPagina($pagina);
	}
	$paginas = [];
	for ($i = 0; $i < $paginacao['paginas']; $i++){
		array_push($paginas, [
			'href'=>'/visitas?'.http_build_query([
				'pagina'=>$i+1,
				'busca'=>$busca
			]),
			'texto'=>$i+1
		]);
	}
	$page = new Page();
	$page->setTpl("visitas", [
		"visitas"=>$paginacao['data'],
		"busca"=>$busca,
		"paginas"=>$paginas,
		"morador"=>$morador->getValues()
	]);
});

$app->get("/visitas/agendar", function(){
	Usuario::verifyLogin(false);
	//$moradores = Morador::listAll();
	$busca = (isset($_GET['busca'])) ? $_GET['busca'] : "";
	$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	if ($busca != ''){
		$paginacao = Morador::getPaginaBusca($busca, $pagina, 5);
	}else{
		$paginacao = Morador::getPagina($pagina);
	}
	$paginas = [];
	for ($i = 0; $i < $paginacao['paginas']; $i++){
		array_push($paginas, [
			'href'=>'/visitas/agendar?'.http_build_query([
				'pagina'=>$i+1,
				'busca'=>$busca
			]),
			'texto'=>$i+1
		]);
	}
	$page = new Page();
	$page->setTpl("moradores-visitas", [
		"moradores"=>$paginacao['data'],
		"busca"=>$busca,
		"paginas"=>$paginas
	]);
});

$app->get("/visitas/:idmorador/agendar", function($idmorador){
	Usuario::verifyLogin(false);
	$morador = new Morador();
	$visita = new Visita();
	$morador->get((int)$idmorador);
	$page = new Page();
	$page->setTpl("visitas-cadastrar", [
		"moradores"=>Morador::listAll(),
		"produtos"=>Produtos::listAll(),
		"visitas"=>Visita::listAll(),
		"morador"=>$morador->getValues(),
		"msgError"=>Visita::getError(),
		"registrarValoresVisitas"=>(!empty($_SESSION["registrarValoresVisitas"])) ? $_SESSION["registrarValoresVisitas"] : ['desdoacao'=>'', 'dtvisita'=>'', 'deshorario'=>'']
	]);
});

$app->post("/visitas/:idmorador/agendar", function($idmorador){
	Usuario::verifyLogin(false);
	$_SESSION['registrarValoresVisitas'] = $_POST;
	$visita = new Visita();
	$morador = new Morador();
	$morador->get((int)$idmorador);
	if(!isset($_POST['desdoacao']) || $_POST['desdoacao'] === ''){
		Visita::setError("Selecione o tipo da doação.");
		header("Location: /visitas/".$idmorador."/agendar");
		exit;
	}
	if(!isset($_POST['dtvisita']) || $_POST['dtvisita'] === ''){
		Visita::setError("Informe a data de agendamento.");
		header("Location: /visitas/".$idmorador."/agendar");
		exit;
	}
	if(!isset($_POST['deshorario']) || $_POST['deshorario'] === ''){
		Visita::setError("Selecione o horário.");
		header("Location: /visitas/".$idmorador."/agendar");
		exit;
	}
	$visita->setidstatus(StatusVisita::EM_ABERTO);
	$_POST['dtvisita'] = implode("-", array_reverse(explode("/", $_POST['dtvisita'])));
	$visita->setData($_POST);
	$visita->save();
	if(!empty($_POST['desemailvisita'])){
	$email = Visita::getEmail($_POST["desemailvisita"]);
	}
	$_SESSION['registrarValoresVisitas'] = NULL;
	header("Location: /visitas");
	exit;
});

$app->get("/visitas/:idvisita/status", function($idvisita){
	Usuario::verifyLogin(false);
	$visita = new Visita();
	$visita->get((int)$idvisita);
	$page = new Page();
	$page->setTpl("visitas-status", [
		"visita"=>$visita->getValues(),
		"statusvisita"=>StatusVisita::listAll(),
		'msgError'=>Visita::getError(),
		'msgSuccess'=>Visita::getSuccess()
	]);
});


$app->post("/visitas/:idvisita/status", function($idvisita){
	Usuario::verifyLogin(false);
	if(!isset($_POST['idstatus']) || !(int)$_POST['idstatus'] > 0){
		Visita::setError("Informe o status atual");
		header("Location: /visitas/".$idvisita."/status");
		exit;
		}
		if((int)$_POST['idstatus'] == 1){
		Visita::setError("Não é possível mudar o status de uma visita já atendida");
		header("Location: /visitas/".$idvisita."/status");
		exit;
	}
		$visita = new Visita();
		$visita->get((int)$idvisita);
		$visita->setidstatus($_POST['idstatus']);
		$visita->save();
		$visita->setSuccess("Status atualizado com sucesso!");
		header("Location: /visitas/".$idvisita."/status");
		exit;
});

$app->get("/visitas/:idvisita/detalhes", function($idvisita){
	Usuario::verifyLogin(false);
	$visita = new Visita();
	$visita->get((int)$idvisita);
	$page = new Page();
	$page->setTpl("visita-detalhes", [
		"visita"=>$visita->getValues(),
		"visitas"=>Visita::listAll()
	]);
});

$app->get("/editar", function(){
	Usuario::verifyLogin(false);
	$usuario = Usuario::getFromSession();
	$page = new Page();
	$page->setTpl("usuarios-editar",[
		"usuario"=>$usuario->getValues(),
		"msgError"=>Usuario::getError(),
		"msgSuccess"=>Usuario::getSuccess()
	]);
});

$app->get("/moradores/:idmorador/detalhes", function($idmorador){
	Usuario::verifyLogin(false);
	$morador = new Morador();
	$morador->get((int)$idmorador);
	$page = new Page();
	$page->setTpl("moradores-detalhes", [
		"morador"=>$morador->getValues()
	]);
});


$app->run();

 ?>
