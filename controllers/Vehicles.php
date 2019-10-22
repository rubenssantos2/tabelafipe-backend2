<?php
namespace controllers{

	class Vehicles{

		private $PDO;

		function __construct(){
			$this->PDO = new \PDO('mysql:host=localhost;dbname=tabela_fipe', 'root', '');
			$this->PDO->setAttribute( \PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION ); 
		}

		public function list(){
			global $app;
			$sth = $this->PDO->prepare("SELECT * FROM veiculos");
			$sth->execute();
			$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
			$app->render('default.php',["data"=>$result],200); 
        }
        
        public function listTop(){
			global $app;
			$sth = $this->PDO->prepare("SELECT * FROM veiculos order by consultas DESC LIMIT 3");
			$sth->execute();
			$result = $sth->fetchAll(\PDO::FETCH_ASSOC);
			$app->render('default.php',["data"=>$result],200); 
		}

		public function get($id){
			global $app;
			$sth = $this->PDO->prepare("SELECT * FROM veiculos WHERE id = :id");
			$sth ->bindValue(':id',$id);
			$sth->execute();
			$result = $sth->fetch(\PDO::FETCH_ASSOC);
			$app->render('default.php',["data"=>$result],200); 
		}


		public function new(){
			global $app;
			$data = json_decode($app->request->getBody(), true);
			$data = (!$data || sizeof($data)==0)? $_POST : $data;
            $keys = array_keys($data);
            
			$sth = $this->PDO->prepare("INSERT INTO veiculos (".implode(',', $keys).") VALUES (:".implode(",:", $keys).")");
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

			$sth = $this->PDO->prepare("UPDATE veiculos SET ".implode(',', $sets)." WHERE id = :id");
			$sth ->bindValue(':id',$id);
			foreach ($data as $key => $value) {
				$sth ->bindValue(':'.$key,$value);
			}

			$app->render('default.php',["data"=>['status'=>$sth->execute()==1]],200); 
		}


		public function delete($id){
			global $app;
			$sth = $this->PDO->prepare("DELETE FROM veiculos WHERE id = :id");
			$sth ->bindValue(':id',$id);
			$app->render('default.php',["data"=>['status'=>$sth->execute()==1]],200); 
        }
        
        public function incrementConsult($id){
            global $app;
			$sth = $this->PDO->prepare("UPDATE veiculos SET consultas = consultas+1 WHERE id = :id");
			$sth ->bindValue(':id',$id);
			$app->render('default.php',["data"=>['status'=>$sth->execute()==1]],200); 
        }
	}
}