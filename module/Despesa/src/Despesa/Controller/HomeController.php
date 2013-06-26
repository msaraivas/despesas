<?php

# namespace de localizacao HomeController.php
namespace Despesa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;     // Add this line
use Despesa\Entity\Item;


class HomeController extends AbstractActionController
{
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }  
    
    
    # action index
    public function indexAction()
    {
    $objectManager = $this
        ->getServiceLocator()
        ->get('Doctrine\ORM\EntityManager');
   
$itemRepository = $objectManager->getRepository('Despesa\Entity\Item');
$items = $itemRepository->findAll();
//  $items = $objectManager->getRepository('Despesa\Entity\Item');
 // $items->
$subitems = $objectManager->getRepository('Despesa\Entity\Subitem')->findAll();
$despesas = $this->getEntityManager()->getRepository('Despesa\Entity\Despesa')->findAll();
/*    $despesa = new \Despesa\Entity\Despesa();
    $despesa->descdespesa = 'Despesa1';
    $despesa->dtdespesa = new \DateTime("now");
    $despesa->valdespesa = 100;
    $despesa->coditem = 1;
    $despesa->codsubitem = 1;    

 //   $objectManager->persist($despesa);
  //  $objectManager->flush();    
*/   
 /*   $item = new \Despesa\Entity\Item();
    $item->descitem = 'Item 9';

    $subitem = new \Despesa\Entity\Subitem();
    $subitem->descsubitem = 'Subitem 9';
    $subitem->item = $item;    
 
    $despesa = new \Despesa\Entity\Despesa();
    $despesa->descdespesa = 'Despesa 1';
    $despesa->valdespesa = 100;
    $despesa->dtdespesa = new \DateTime("now");
    $despesa->subitem = $subitem;  
 /*   
    $objectManager->persist($item);
    $objectManager->flush();
    
    $objectManager->persist($subitem);
   // $objectManager->flush();    
    
  //  $objectManager->persist($despesa);
   // $objectManager->flush();   //   
    */
   // $result = $this->getEntityManager()->getRepository('Despesa\Entity\Despesa')->findAll();
    //var_dump($subitems); 
     var_dump($subitems); 
   //var_dump($despesas);
       
 
    }

}
