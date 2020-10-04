<?php

use TH\modelos\Usuario;
use TH\modelos\Moradores;

function checkLogin($inadmin = true) {
	return Usuario::checkLogin($inadmin);
}

function getUserName() {     
    $usuario = Usuario::getFromSession();     
    $usuario->get($usuario->getidusuario());      
    return $usuario->getdespessoa();
}

function datePt(){
	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8','portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	$results = strftime('%A, %d de %B de %Y', strtotime('today'));
	return utf8_encode($results);
}
function formatDate($date){
	return date('d/m/Y', strtotime($date));
}

function formatDtVisita($dtvisita){
	return $dtvisita = implode("/", array_reverse(explode("-", $dtvisita)));
}
?>