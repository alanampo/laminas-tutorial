<?php

namespace Company\Model;

class Employee implements EmployeeInterface
{
    /**
     * @var int
     */
    private $idEmployee;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $codiceFiscale;

    // public function __construct($idEmployee = null, $firstName, $codiceFiscale)
    // {
    //     $this->idEmployee=$idEmployee;
    //     $this->firstName=$firstName;
    //     $this->codiceFiscale=$codiceFiscale;
    // }

    public function getId()
    {
        return $this->idEmployee;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getCodiceFiscale()
    {
        return $this->codiceFiscale;
    }
}