<?php

// namespace de localizacao ContatosController.php
namespace Despesa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;
use Despesa\Entity\Despesa;
use Despesa\Entity\Subitem;
use Despesa\Entity\Item;



class DespesasController extends AbstractActionController
{

    /**             
     * @var Doctrine\ORM\EntityManager
     */                
    protected $em;

  
    
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }    
    
    // GET 
    public function indexAction()
    {        
        return new ViewModel(array(
            //'despesas' => $this->getEntityManager()->getRepository('Despesa\Entity\Despesa')->findAll(),
           // 'despesas' => $this->getEntityManager()->createQuery('SELECT d FROM Despesa\Entity\Despesa d WHERE d.id > 0 ')
        ));            
    }

    // 
    public function listdespesasAction()
    {
        //Get records from database
        $jtSorting      = $_GET["jtSorting"] ; 
        $jtPageSize     = $_GET["jtPageSize"]; 
        $jtStartIndex   = $_GET["jtStartIndex"]; 
                
        $query = $this->getEntityManager()->createQuery('SELECT d, s, i FROM Despesa\Entity\Despesa d JOIN d.subitem s JOIN s.item i ');// INDEX BY DESC '); //) . $_GET["jtSorting"]);
        
        $i = 0;
        //$result = $despesas->getResult();
        $result = $query->getArrayResult();
        $recordCount = count($result);
        
        $query = $this->getEntityManager()->createQuery('SELECT d, s, i FROM Despesa\Entity\Despesa d JOIN d.subitem s JOIN s.item i ORDER BY d.'.$_GET["jtSorting"] );// INDEX BY DESC '); //) . $_GET["jtSorting"]);
        $query->setMaxResults($_GET["jtPageSize"]);
        $query->setFirstResult($_GET["jtStartIndex"]); 
        $result = $query->getArrayResult();        
        
        // formata alguns campos do array  
        foreach ($result as $row) {
            $result[$i]['dtdespesa']   =  $result[$i]['dtdespesa']->format('Y-m-d');
            $result[$i]['iditem']      =  $result[$i]['subitem']['item']['id'];  
            $result[$i]['idsubitem']   =  $result[$i]['subitem']['id'];                        
            $i = $i +1;           
        }
       
        //Return result to jTable
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $result;
        return new JsonModel($jTableResult);
      // die(var_dump($despesas2)); 
    }

    // 
    public function listoptionitemAction()
    {
        //Get records from database
        //$where = ' coditem  = ".$_GET["coditem"]. " ORDER BY descsubitem " );
        $query = $this->getEntityManager()->createQuery('SELECT i FROM Despesa\Entity\Item i ORDER BY i.id  ');
	$result = $query->getArrayResult();
        
        //Add all records to an array
        $rows = array();   
        foreach ($result as $row) {
            $rows[]= array('DisplayText' => $row['descitem'], 'Value' => $row['id']);
        }       
                  
        //Return result to jTable
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Options'] = $rows;
        return new JsonModel($jTableResult);   
    }

    // 
    public function listoptionsubitemAction()
    {
        //Get records from database
        //$result = pg_query("SELECT id, descsubitem FROM subitem where coditem  = ".$_GET["coditem"]. " ORDER BY descsubitem " );
        $iditem = $this->params()->fromRoute('id', $default);
        if      ($iditem == 0)    {$where = ' ';} 
        else if ($iditem != 0)    {$where =  ' WHERE s.item = ' .$iditem; }; 
        //$where = ' ';
        $query = $this->getEntityManager()->createQuery('SELECT s, i FROM Despesa\Entity\Subitem s JOIN s.item i '.$where);// INDEX BY DESC '); //) . $_GET["jtSorting"]);
	$result = $query->getArrayResult();
        
        //Add all records to an array
        $rows = array();   
        foreach ($result as $row) {
            $rows[]= array('DisplayText' => $row['descsubitem'].'-'.$row['item']['id'], 'Value' => $row['id']);
        }       
                  
        //Return result to jTable
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Options'] = $rows;
        return new JsonModel($jTableResult); 
        var_dump($result); 
    }

    // 
    public function createAction()
    {
		//Insert record into database
     //   try
     //   {
            //$query = $this->getEntityManager()->find('Despesa\Entity\Subitem', 5); //$_POST["idsubitem"]);
            //$query = $this->getEntityManager()->createQuery("SELECT s FROM Despesa\Entity\Subitem s WHERE s.id = 5");
            //$subitem = $query->getResult(); 
            
            $items = $this->getEntityManager()->getRepository('Despesa\Entity\Item')->find(1);//$_POST["iditem"]);
            $subitem = $this->getEntityManager()->getRepository('Despesa\Entity\Subitem')->find(7);//$_POST["idsubitem"]);
            
            $data = '11/11/1111'; //$_POST["dtdespesa"];
            $entity = new \Despesa\Entity\Despesa();
            $entity->descdespesa = $data;
            //$entity->dtdespesa = new \DateTime("now");
            $entity->valdespesa = 500; //$_POST["valdespesa"];
            $entity->dtdespesa = new \DateTime($data);
            $entity->subitem = $subitem;
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush(); 
           // $entity->subitem = $subitem->id;
            $query = $this->getEntityManager()->createQuery('SELECT d FROM Despesa\Entity\Despesa d  WHERE d.id = '. $entity->id);// INDEX BY DESC '); //) . $_GET["jtSorting"]);
            $result = $query->getArrayResult();
            
        // formata alguns campos do array  
        foreach ($result as $row) {
            $result2['dtdespesa']   =  $result[0]['dtdespesa']->format('Y-m-d');
            $result2['idsubitem']   =  $subitem->id;  
            $result2['valdespesa']   =  (float)$result[0]['valdespesa'];
            $result2['id']   =       $result[0]['id'];
            $result2['descdespesa']   =  $result[0]['descdespesa'];
            $result2['iditem']      =  $subitem->item->id; 
           
        }
        
        
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Records'] = $result2;
            return new JsonModel($jTableResult); 

      /*      var_dump($data);
            var_dump($result);
            var_dump($jTableResult);
            print json_encode($jTableResult);
        */
     /*   } catch (Exception $e) 
        {
            $jTableResult = array();
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = $e;
            return new JsonModel($jTableResult);             
        } */    
    }

    // 
    public function updateAction()
    {
        //Update from database
        $query = $this->getEntityManager()->createQuery("UPDATE Despesa\Entity\Despesa d SET d.valdespesa = ".$_POST["valdespesa"]. ", d.dtdespesa = '".$_POST["dtdespesa"]. "'  WHERE d.id = " . $_POST["id"]);
        $result = $query->getArrayResult();
        
        //Return result to jTable . $_POST["id"] .
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Records'] = $result;
        return new JsonModel($jTableResult); 
        //var_dump($result); 
    }

    // 
    public function deleteAction()
    {
        //Delete from database
        $query = $this->getEntityManager()->createQuery("DELETE Despesa\Entity\Despesa d WHERE d.id = ". $_POST["id"]);
        $result = $query->getArrayResult();

        //Return result to jTable . $_POST["id"] .
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Records'] = $result;
        return new JsonModel($jTableResult); 
        //var_dump($query);        
    }    

}
