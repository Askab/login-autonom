DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getEmployeeData`(IN `ParamLimit` INT(2), IN `ParamOffset` INT(3))
SELECT emp.*, dep.dept_name AS 'osztaly' FROM (
            SELECT e.*, sal.salary, til.title FROM employees e INNER JOIN salaries sal ON sal.emp_no = e.emp_no INNER JOIN titles til ON til.emp_no = e.emp_no WHERE sal.to_date AND til.to_date > NOW() GROUP BY e.emp_no
        ) AS emp
            INNER JOIN dept_emp dee ON emp.emp_no = dee.emp_no
            INNER JOIN departments dep ON dee.dept_no = dep.dept_no ORDER BY emp.first_name ASC,emp.last_name ASC  LIMIT ParamLimit OFFSET ParamOffset
$$ DELIMITER ;