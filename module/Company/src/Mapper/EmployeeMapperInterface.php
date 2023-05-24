<?php 
namespace Company\Mapper;

use Company\Model\EmployeeInterface;

interface EmployeeMapperInterface
{
    
    public function fetchAll();
    
    public function find($employee_id);    
    
    public function save(EmployeeInterface $employeeObject);
    
    public function delete(EmployeeInterface $employeeObject);
    
}