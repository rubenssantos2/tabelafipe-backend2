<?php
namespace controllers{

	class Consults{

		private $PDO;

		function __construct(){
			$this->PDO = new \PDO('mysql:host=localhost;dbname=tabela_fipe', 'root', '');
			$this->PDO->setAttribute( \PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION ); 
		}

		public function list(){
			global $app;
			$sth = $this->PDO->prepare(
                "SELECT v.id, v.ano, v.marca, v.nome, v.veiculo, v.preco, v.combustivel, v.referencia, v.codigo_fipe, c.data FROM veiculos v JOIN consultas c on (c.id_veiculo = v.id)"
            );
			$sth->execute();
			$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
			$app->render('default.php',["data"=>$result],200); 
		}

		public function get($id){
			global $app;
			$sth = $this->PDO->prepare("SELECT * FROM consultas WHERE id = :id");
			$sth ->bindValue(':id',$id);
			$sth->execute();
			$result = $sth->fetch(\PDO::FETCH_ASSOC);
			$app->render('default.php',["data"=>$result],200); 
		}


		public function new(){
			global $app;
			$data = json_decode($app->request->getBody(), true);
			$data = (sizeof($data)==0)? $_POST : $data;
            $keys = array_keys($data);
            
			$sth = $this->PDO->prepare("INSERT INTO consultas (".implode(',', $keys).") VALUES (:".implode(",:", $keys).")");
			foreach ($data as $key => $value) {
				$sth ->bindValue(':'.$key,$value);
			}
			$sth->execute();

			$app->render('default.php',["data"=>['id'=>$this->PDO->lastInsertId()]],200); 
		}


		public function edit($id){
			global $app;
			$data = json_decode($app->request->getBody(), true);
			$data = (sizeof($data)==0)? $_POST : $data;
			$sets = [];
			foreach ($data as $key => $VALUES) {
				$sets[] = $key." = :".$key;
			}

			$sth = $this->PDO->prepare("UPDATE consultas SET ".implode(',', $sets)." WHERE id = :id");
			$sth ->bindValue(':id',$id);
			foreach ($data as $key => $value) {
				$sth ->bindValue(':'.$key,$value);
			}

			$app->render('default.php',["data"=>['status'=>$sth->execute()==1]],200); 
		}


		public function delete($id){
			global $app;
			$sth = $this->PDO->prepare("DELETE FROM consultas WHERE id = :id");
			$sth ->bindValue(':id',$id);
			$app->render('default.php',["data"=>['status'=>$sth->execute()==1]],200); 
		}
	}
}