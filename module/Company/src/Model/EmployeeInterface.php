<?php 
namespace Company\Model;

interface EmployeeInterface
{
    /**
     * Will return the ID of the Employee
     *
     * @return int
     */
    public function getId();
    
    /**
     * Will return the NAME of the Employee
     *
     * @return string
     */
    public function getFirstName();
    
    /**
     * Will return the CODICE FISCALE of the Employee
     *
     * @return string
     */
    public function getCodiceFiscale();
}
