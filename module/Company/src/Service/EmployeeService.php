<?php

namespace Company\Service;

use Company\Mapper\EmployeeMapperInterface;
use Company\Model\EmployeeInterface;

class EmployeeService implements EmployeeServiceInterface 
{
    
    protected $employeeMapper;
    
    public function __construct($employeeMapper)
    {
        $this->employeeMapper=$employeeMapper;
    }
    
    public function findAll()
    {
        return $this->employeeMapper->findAll();
    }

    public function prova(){
        //$v = $this->employeeMapper->provaMapper();

        $result = $this->employeeMapper->fetchAll()->toArray();
        
        return $result;
    }
   
    public function find($employee_id)
    {
        return $this->employeeMapper->find($employee_id);
    }
  
    public function insert(EmployeeInterface $employee)
    {
        return $this->employeeMapper->save($employee);
    }
    
  
    public function update(EmployeeInterface $employee)
    {
        return $this->employeeMapper->save($employee);
    }
    
    public function delete(EmployeeInterface $employee)
    {
        return $this->employeeMapper->delete($employee);
    }
}
