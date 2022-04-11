<?php

include_once 'bootstrap.php';

$url = $_SERVER['REQUEST_URI'];

$segment = '';
if(preg_match('#index\.php\/([\w-]+)#ui',$url,$match)){
    $segment = trim($match[1]);
}

/**
 * Small routing
 */
if($segment == 'loadData'){

    $page = (isset($_POST['page']) > 0) ? filter_input(INPUT_POST,'page',FILTER_VALIDATE_INT) : 1;

    $employeeData = $repository->getEmployeeData($page);

    $employeeData['pageData']['nextPage'] = $page + 1;

    echo json_encode($employeeData);
	
} else if ($segment == 'editEmployee'){
	
	$data = [
		'id' => 10001,
		'first_name' => 'Joe' //Georgi
	];
	
	$repository->editEmployee($data);
	
} else if ($segment == 'deleteEmployee'){
	
	$id = 10001;
	$repository->deleteEmployee($id);
}