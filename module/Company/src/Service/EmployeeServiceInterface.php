<?php 
namespace Company\Service;

use Company\Model\EmployeeInterface;

interface EmployeeServiceInterface
{

    public function findAll();

    public function find($employee_id);
    
    public function insert(EmployeeInterface $employee);
        
    public function update(EmployeeInterface $employee);
    
    public function delete(EmployeeInterface $employee);
    
}