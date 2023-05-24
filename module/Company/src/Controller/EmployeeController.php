<?php

namespace Company\Controller;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class EmployeeController extends AbstractActionController
{

    private $employeeService;
    public function __construct($employeeService)
    {
        $this->employeeService = $employeeService;

    }

    public function indexAction()
    {
        $a = $this->employeeService->prova();
        
        return new ViewModel([
            "data" => $a
        ]);
    }

}