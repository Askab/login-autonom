<?php

class Data {
	
	/*
	* @var PDO objektum
	*/
    protected \PDO $pdo;

	/**
	* @param \PDO PDO objektum
	*/
    public function __construct($pdo)
    { 
        $this->pdo = $pdo;  
    }

    public function getPdo(){
        return $this->pdo;
    }

    public function setPdo($pdo){
        $this->pdo = $pdo;
    }

	/**
	* Rekordok törlése a megadott feltétel alapján
	*
	* @param string Az SQL lekérdezés
	* @param array A lekérdezéshez tartozó szűrő adatok tömbje
	* @return array
	*/
    public function selectData($query,$data = []) : array {
        $stmt = $this->pdo->prepare($query);
        $result = $stmt->execute($data);

        if($result != false){
            return $stmt->fetchAll();
        } else {
            return [];
        }
    }

	/**
	* Rekordok frissítése a megadott feltétel alapján
	*
	* @param string a tábla neve
	* @param array A friss adatok tömbje
	* @param array Szűrő paraméterek
	* @param integer Limit
	* @return int
	*/
    public function updateData($table,$data,$where,$limit = 1){
        $query = 'UPDATE ' . $table . ' SET ';
        $realData = [];

        foreach($data as $key =>$val){
            $dataKey = ':' . $key;
            $realData[$dataKey] = $val;

            $query .= $key . '=' . $dataKey . ',';
        }

        $query = rtrim($query,',') . ' WHERE ' . $where['string'] . ' LIMIT ' . $limit . ';';

        //Bejárja a tömböt,és a callbak az adott elemmel dolgozik
        array_walk($where['data'],function(&$item,$key){
            $key = ':' . $key;
        });

        // Két tömb egyesítése array_merge();
        $realData = array_merge($realData,$where['data']);

        $stmt= $this->pdo->prepare($query);
        return $stmt->execute($realData);
    }

	/**
	* Rekordok törlése a megadott feltétel alapján
	*
	* @param string a tábla neve
	* @param array Szűrő paraméterek
	* @param integer Limit
	* @return int
	*/
    public function deleteData($table,$where,$limit = 1){
        $query = 'DELETE FROM ' . $table . ' WHERE ' . $where['string'] . ' LIMIT ' . $limit . ';';

        //Bejárja a tömböt,és a callbak az adott elemmel dolgozik
        array_walk($where['data'],function(&$item,$key){
            $key = ':' . $key;
        });

        $stmt= $this->pdo->prepare($query);
        return $stmt->execute($where['data']);
    }
}