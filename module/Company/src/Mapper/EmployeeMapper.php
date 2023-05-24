<?php
namespace Company\Mapper;

use Company\Mapper\EmployeeMapperInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Sql;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Db\Adapter\AdapterInterface;

use Company\Model\EmployeeInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;

class EmployeeMapper implements EmployeeMapperInterface
{
    /**
     * @var AdapterInterface
     */
    private $dbAdapter;
    
    /**
     * @var HydratorInterface
     */
    private $hydrator;
    
    /**
     * @var EmployeeInterface
     *      */
    private $employee;
    
    public function __construct(
        AdapterInterface $dbAdapter,
        HydratorInterface $hydrator,
        EmployeeInterface $employee
        ) {
            $this->dbAdapter     = $dbAdapter;
            $this->hydrator      = $hydrator;
            $this->employee = $employee;
    }
    
    public function provaMapper(){
        return "PLUTO";
    }

    public function fetchAll()
    {
        
        $sql       = new Sql($this->dbAdapter);
        $select    = $sql->select('employee');

        //echo $select->getSqlString( $this->dbAdapter->getPlatform() ); die;

        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        
        if (! $result instanceof ResultInterface || ! $result->isQueryResult()) {
            return [];
        }

        $resultSet = new HydratingResultSet($this->hydrator, $this->employee);
        $resultSet->initialize($result);
        
        return $resultSet;
    }
    
    public function find($employee_id){}
    
    public function save(EmployeeInterface $employeeObject){}
    
    public function delete(EmployeeInterface $employeeObject){}
   
}