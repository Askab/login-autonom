<?php

class Repository {

    protected Data $data;
	protected int $employeeLimit = 20;
	
	protected array $tables = [
		'employees' => 'employees'
	];

    public function __construct($data) {
        $this->data = $data;
    }

	/**
	* Dolgozó adatainak lekérdezése lekérdezése 
	*
	* @param integer $page AZ aktuális oldal száma
	* @return array DOlgozói adatok
	*/
    public  function getEmployeeData($page = 0){
      $sql = "CALL getEmployeeData(:lmt,:offset)";

        $total = $this->getEmployeeCount()[0]['total'];
        $limit = $this->employeeLimit;
        $offset = ($page - 1)  * $limit;

        $pages = ceil($total / $limit);

        return [
            'data' => $this->data->selectData($sql,[":lmt" => $limit,":offset" => $offset]),
            'pageData' => [
                'pages' => $pages,
                'total' => $total
            ]
        ];
    }

	/*
	* Dolgozó adatok számának lekérdezése
	* @return array
	*/
    public function getEmployeeCount () {
        $totalQuery = "SELECT COUNT(*) AS 'total' FROM (
            SELECT e.*, sal.salary, til.title FROM employees e INNER JOIN salaries sal ON sal.emp_no = e.emp_no INNER JOIN titles til ON til.emp_no = e.emp_no WHERE sal.to_date AND til.to_date > NOW() GROUP BY e.emp_no
        ) AS emp
            INNER JOIN dept_emp dee ON emp.emp_no = dee.emp_no
            INNER JOIN departments dep ON dee.dept_no = dep.dept_no
            LIMIT 1";

        return $this->data->selectData($totalQuery,[]);
    }
	
	/**
	* Dolgozó adatainak frissítése
	* @param array DOlgozó adatai
	* @return integer
	*/
	public function editEmployee($updateData){
		
		$id = $updateData['id'];
		unset($updateData['id']);
		
		return $this->data->updateData(
			$this->tables['employees'],
			$updateData,
			[
				'string' => 'emp_no = :emp_no',
				'data' => ['emp_no' => (int)$id]
			]
		);
	}
	
	/**
	* Dolgozó adatainak frissítése
	* @param integer Dolgozó azonosítószáma
	* @return integer
	*/
	public function deleteEmployee($id){
		return $this->data->deleteData(
			$this->tables['employees'],
			[
				'string' => 'emp_no = :emp_no',
				'data' => ['emp_no' => (int)$id]
			]
		);
	}
}