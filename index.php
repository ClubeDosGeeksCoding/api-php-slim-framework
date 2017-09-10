<?php

//Autoload
$loader = require 'vendor/autoload.php';

//configuracoes para exibir erros no SLim, pode ser comentado, apenas para localhost
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

//Instanciando objeto
$app = new \Slim\App($configuration);

//quando não é chamado nenhum método
$app->get('/', function() {
	echo "Nenhum método foi chamado";
});

//Listando todas
$app->get('/pessoas/', function() {
	//$this->response->withJson() transforma o output em json e manda o header correto
	return $this->response->withJson((new \controllers\Pessoa())->lista(),201);

});

//get pessoa
$app->get('/pessoas/{id}', function( $request , $response , $args ) {
	$id = $args['id'];

	return $response->withJson( (new \controllers\Pessoa())->get($id) );
});

//nova pessoa
$app->post('/pessoas/', function( $request, $response ) use ( $app ) {

	return $response->withJson( (new \controllers\Pessoa($app))->nova() );
});

//edita pessoa
$app->put('/pessoas/{id}', function( $request, $response, $args ) use ( $app ) {
	
	$id = $args['id'];

	return $response->withJson( (new \controllers\Pessoa($app))->editar($id) );

});

//apaga pessoa
$app->delete('/pessoas/{id}', function( $request, $response, $args ) {
	
	$id = $args['id'];
	
	return $response->withJson( (new \controllers\Pessoa())->excluir($id) );
});

//Rodando aplicação
$app->run();