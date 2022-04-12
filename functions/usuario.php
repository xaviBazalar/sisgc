<?php
	include '../define_con.php';
	
	require_once('usuario.class.php');
	$usuario = new Usuario();
	$usuario->nombre="Javier";
	$usuario->doc="+5665465";
	$usuario->fn="2012-05-12";
	$usuario->dom="PEru";
	$usuario->fono="564546";
	$usuario->email="javi46@hotmail.com";
	$usuario->usrs="ddd";
	$usuario->upas=md5("ddd");
	$usuario->fi="2012-05-12";
	$usuario->cart="1";
	$usuario->niv="1";
	$usuario->est="1";
	$usuario->dpto="01";
	$usuario->prov="02";
	$usuario->dist="10";
	$usuario->prove="1";
	echo "<pre>";
	echo $usuario->nombre;
	foreach($usuario as  $user){
		echo $user."<br/>";
	}
	
	$function = new UsuarioDAO();
			$function->insert($usuario,$db);