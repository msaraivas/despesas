<?php

# namespace de localizacao HomeController.php
namespace Despesa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;     // Add this line


class HomeController extends AbstractActionController
{

    # action index
    public function indexAction()
    {
    $objectManager = $this
        ->getServiceLocator()
        ->get('Doctrine\ORM\EntityManager');

    $despesa = new \Despesa\Entity\Despesa();
    $despesa->descdespesa = 'Despesa1';
    $despesa->dtdespesa = new \DateTime("now");
    $despesa->valdespesa = 100;
    $despesa->coditem = 1;
    $despesa->codsubitem = 1;    

    $objectManager->persist($despesa);
    $objectManager->flush();    
   
    $item = new \Despesa\Entity\Item();
    $item->descitem = 'Item1';
    $item->codsubitem = 1;

    $objectManager->persist($item);
    $objectManager->flush();
    die(var_dump($item)); 
    die(var_dump($despesa));
       
 
    }

}
