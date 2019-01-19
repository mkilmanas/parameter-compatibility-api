<?php
declare(strict_types=1);


namespace App\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;

class ParameterController extends AbstractFOSRestController
{
    function cgetAction()
    {
        $data = [];

        return $this->handleView(
            $this->view($data)
        );
    }
}