<?php
namespace controllers{
	/*
	Classe pessoa
	*/
	class Pessoa{
		//Atributo para banco de dados
		private $PDO;

		/*
		__construct
		Conectando ao banco de dados
		*/
		function __construct(){
			$this->PDO = new \PDO('mysql:host=localhost;dbname=api', 'root', ''); //Conexão
			$this->PDO->setAttribute( \PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION ); //habilitando erros do PDO
		}

		/*
		lista
		Listand pessoas
		*/
		public function lista(){

			$sth = $this->PDO->prepare("SELECT * FROM pessoa");
			$sth->execute();
			$result = $sth->fetchAll(\PDO::FETCH_ASSOC);

			return $result;
		}
		/*
		get
		param $id
		Pega pessoa pelo id
		*/
		public function get($id){

			$sth = $this->PDO->prepare("SELECT * FROM pessoa WHERE id = :id");
			$sth ->bindValue( ':id' , $id );
			$sth->execute();
			$result = $sth->fetch(\PDO::FETCH_ASSOC);

			//retorna os dados para ser tratado via withJson
			return ["data"=>$result];
		}

		/*
		nova
		Cadastra pessoa
		*/
		public function nova(){
			global $app;

			//recupera a variável request do $app
			$container = $app->getContainer();
			$request = $container['request'];

			//recupera as variáveis enviadas via post
			$dados = $request->getParsedBody();
			$dados = (sizeof($dados)==0)? $_POST : $dados;
			$keys = array_keys($dados); //Paga as chaves do array

			/*
			O uso de prepare e bindValue é importante para se evitar SQL Injection
			*/

			$sth = $this->PDO->prepare("INSERT INTO pessoa (".implode(',', $keys).") VALUES (:".implode(",:", $keys).")");
			foreach ($dados as $key => $value) {
				$sth ->bindValue(':'.$key,$value);
			}
			$sth->execute();
			//Retorna o id inserido
			return ["data"=>['id'=>$this->PDO->lastInsertId()]];

		}

		/*
		editar
		param $id
		Editando pessoa
		*/
		public function editar($id){
			global $app;

			$container = $app->getContainer();
			$request = $container['request'];

			$dados = $request->getParsedBody();
			$dados = (sizeof($dados)==0)? $_POST : $dados;
			$sets = []; //criar variável array PHP >= 5.4
			foreach ($dados as $key => $VALUES) {
				$sets[] = $key." = :".$key;
			}

			$sth = $this->PDO->prepare("UPDATE pessoa SET ".implode(',', $sets)." WHERE id = :id");
			$sth ->bindValue(':id',$id);
			foreach ($dados as $key => $value) {
				$sth ->bindValue(':'.$key,$value);
			}
			//Retorna status da edição
			return ["data"=>['status'=>$sth->execute()==1]];
			//$app->render('default.php',["data"=>['status'=>$sth->execute()==1]],200); 
		}

		/*
		excluir
		param $id
		Excluindo pessoa
		*/
		public function excluir($id){

			$sth = $this->PDO->prepare("DELETE FROM pessoa WHERE id = :id");
			$sth ->bindValue(':id',$id);
			return ["data"=>['status'=>$sth->execute()==1]];
			
		}
	}
}