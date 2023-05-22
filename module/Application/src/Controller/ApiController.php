<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class ApiController extends AbstractActionController
{
    public function indexAction()
    {
        $json =
            array(
                'name' => 'Alan Ampo',
                'email' => 'alanampo@gmail.com',
            );

        $viewModel = new JsonModel($json);
        //$viewModel->setVariable('data', $json);
        //$viewModel->setVariable('', $json);
       
        return $viewModel;

    }
}