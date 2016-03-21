<?php
namespace controllers{
	class Pessoa{
		private $PDO;

		function __construct(){
			$this->PDO = new \PDO('mysql:host=localhost;dbname=api', 'root', '');
			$this->PDO->setAttribute( \PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION );
		}

		public function lista(){
			global $app;
			$sth = $this->PDO->prepare("SELECT * FROM pessoa");
			$sth->execute();
			$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
			$app->render('default.php',["data"=>$result],200); 
		}

		public function get($id){
			global $app;
			$sth = $this->PDO->prepare("SELECT * FROM pessoa WHERE id = :id");
			$sth ->bindValue(':id',$id);
			$sth->execute();
			$result = $sth->fetch(\PDO::FETCH_ASSOC);
			$app->render('default.php',["data"=>$result],200); 
		}

		public function nova(){
			global $app;
			$dados = json_decode($app->request->getBody(), true);
			$dados = (sizeof($dados)==0)? $_POST : $dados;

			$keys = array_keys($dados);
			$sth = $this->PDO->prepare("INSERT INTO pessoa (".implode(',', $keys).") VALUES (:".implode(",:", $keys).")");
			foreach ($dados as $key => $value) {
				$sth ->bindValue(':'.$key,$value);
			}
			$sth->execute();
			//Retorna o id inserido
			$app->render('default.php',["data"=>['id'=>$this->PDO->lastInsertId()]],200); 
		}

		public function editar($id){
			global $app;
			$dados = json_decode($app->request->getBody(), true);
			$dados = (sizeof($dados)==0)? $_POST : $dados;
			$sets = [];
			foreach ($dados as $key => $VALUES) {
				$sets[] = $key." = :".$key;
			}

			$sth = $this->PDO->prepare("UPDATE pessoa SET ".implode(',', $sets)." WHERE id = :id");
			$sth ->bindValue(':id',$id);
			foreach ($dados as $key => $value) {
				$sth ->bindValue(':'.$key,$value);
			}
			//Retorna status da edição
			$app->render('default.php',["data"=>['status'=>$sth->execute()==1]],200); 
		}

		public function excluir($id){
			global $app;
			$sth = $this->PDO->prepare("DELETE FROM pessoa WHERE id = :id");
			$sth ->bindValue(':id',$id);
			$app->render('default.php',["data"=>['status'=>$sth->execute()==1]],200); 
		}
	}
}