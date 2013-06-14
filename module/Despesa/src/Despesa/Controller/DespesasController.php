<?php

// namespace de localizacao ContatosController.php
namespace Despesa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel; 
use Doctrine\ORM\EntityManager;
use Despesa\Entity\Despesa;



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
            'despesas' => $this->getEntityManager()->getRepository('Despesa\Entity\Despesa')->findAll() 
        ));            
    }

    // 
    public function listlancamentosAction()
    {
		$coditem        = $_POST["coditem"]; 
                $dtdespesa      = $_POST["dtdespesa"];
                $anodespesa     = $_POST["anodespesa"]; 
                $codsubitem     = $_POST["codsubitem"]; 
                $jtSorting      = $_GET["jtSorting"] ; 
                $jtPageSize     = $_GET["jtPageSize"]; 
                $jtStartIndex   = $_GET["jtStartIndex"]; 
                
		if ($_POST["coditem"]    > 0)  { $where = $where . " and coditem = " . $_POST["coditem"] . " ";};
                if ($_POST["codsubitem"] > 0)  { $where = $where . " and codsubitem = " . $_POST["codsubitem"] . " ";};                
                if ($_POST["dtdespesa"]  > 0)  { $where = $where . " and (EXTRACT(MONTH FROM dtdespesa) = ".$dtdespesa.") ";};                
                if ($_POST["anodespesa"] > 0)  { $where = $where . " and (EXTRACT(YEAR FROM dtdespesa) = ".$anodespesa.") ";}; 
                //Get record count
		$result = pg_query("SELECT COUNT(*) AS RecordCount FROM despesa  where id > 0 ". $where. " ");
		$row = pg_fetch_array($result);
		$recordCount = $row['recordcount']; // sempre em minusculo no postgresql

		//Get records from database                
                $result = pg_query("SELECT * FROM despesa where id > 0  ". $where. " ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtPageSize"] . " OFFSET " . $_GET["jtStartIndex"]  .";");
		
		//Add all records to an array
		$rows = array();
		while($row = pg_fetch_array($result))
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);        
    }

    // 
    public function listitemAction()
    {
 		//Get records from database
		$result = pg_query("SELECT * FROM item ORDER BY descitem " );
		//$result = pg_query("SELECT * FROM despesa ORDER BY " . $_GET["jtSorting"] . " LIMIT " .  $_GET["jtPageSize"]  .  " OFFSET " . $_GET["jtStartIndex"] . ";");
		
		//Add all records to an array
		$rows = array();
		while($row = pg_fetch_array($result))
		{
                    $rows[$row['id']] = $row['descitem'];                     
		}                
                
                if ($_GET["tipo"] == 'geral') 
                {
                    $rows1[0] = array_values($rows);
                    $rows1[1] = array_keys($rows);
                    print json_encode(array_values($rows1));
                }
                else if ($_GET["tipo"] == 'lanc'){
                    //Return result to jTable
                    $jTableResult = array();
                    print json_encode($rows);
                }        
    }

    // 
    public function listsubitemAction()
    {
 		//Get records from database
		$result = pg_query("SELECT id, descsubitem FROM subitem where coditem  = ".$_GET["coditem"]. " ORDER BY descsubitem " );
		//$result = pg_query("SELECT * FROM despesa ORDER BY " . $_GET["jtSorting"] . " LIMIT " .  $_GET["jtPageSize"]  .  " OFFSET " . $_GET["jtStartIndex"] . ";");  
		
		//Add all records to an array
		$rows = array();
                   $rows[0] = 'Todos';  
		while($row = pg_fetch_array($result))
		{
                    $rows[$row['id']] = $row['descsubitem'];                     
		}

                if ($_GET["tipo"] == 'geral') 
                {
                    $rows1[0] = array_values($rows);
                    $rows1[1] = array_keys($rows);
                    print json_encode(array_values($rows1));
                }
                else if ($_GET["tipo"] == 'lanc'){
                    //Return result to jTable
                    $jTableResult = array();
                    print json_encode($rows);
                }        
    }

    // 
    public function listtotalmesAction()
    {
 		//Get records from database
		$result = pg_query("SELECT EXTRACT(MONTH FROM dtdespesa) as mes, sum(valdespesa) as valor FROM despesa  where EXTRACT(YEAR FROM dtdespesa) = ". $_GET["ano"]. " group by mes " );		
		$rows = array();                
                $rows["JAN"] = 0;
                $rows["FEV"] = 0;  
                $rows["MAR"] = 0;  
                $rows["ABR"] = 0;  
                $rows["MAI"] = 0; 
                $rows["JUN"] = 0;  
                $rows["JUL"] = 0;   
                $rows["AGO"] = 0;   
                $rows["SET"] = 0;  
                $rows["OUT"] = 0;  
                $rows["NOV"] = 0;  
                $rows["DEZ"] = 0;                   
                                             
		while($row = pg_fetch_array($result))
                {                
                    switch ($row['mes'])
                    { 
                        case 1: $mes = "JAN"; break;
                        case 2: $mes = "FEV"; break;
                        case 3: $mes = "MAR"; break;
                        case 4: $mes = "ABR"; break;
                        case 5: $mes = "MAI"; break;
                        case 6: $mes = "JUN"; break;
                        case 7: $mes = "JUL"; break;
                        case 8: $mes = "AGO"; break;
                        case 9: $mes = "SET"; break;
                        case 10: $mes = "OUT"; break;
                        case 11: $mes = "NOV"; break;
                        case 12: $mes = "DEZ"; break;
                    }
                    $rows[$mes] = (float)$row['valor'];  
		}
                $rows1[0] = array_values($rows);
                $rows1[1] = array_keys($rows);                
                print json_encode(array_values($rows1));        
    }

    // 
    public function listtotalitemAction()
    {
 		//Get records from database
		if($_GET["tipo"] == "anual")
                    {$result = pg_query("SELECT  descitem, sum(valdespesa) as valor FROM despesa, item where item.id = coditem and EXTRACT(YEAR FROM dtdespesa) = ". $_GET["ano"]. "   group by descitem " );}
		else if ($_GET["tipo"] == "mensal")
                    {$result = pg_query("SELECT  descitem, sum(valdespesa) as valor FROM despesa, item where item.id = coditem  and  EXTRACT(MONTH FROM dtdespesa)  = ".$_GET["mes"]." and EXTRACT(YEAR FROM dtdespesa) = ". $_GET["ano"]. " group by descitem " );}		
		//Add all records to an array
		$rows = array();      
		while($row = pg_fetch_array($result))
		{
                    $rows[$row['descitem']] = (float)$row['valor'];  
		}                               
                $rows1[0] = array_values($rows);
                $rows1[1] = array_keys($rows);                
                print json_encode($rows1);         
    }

    // 
    public function listtabelaAction()
    {
            for ($i = 1; $i <= 12; $i++) 
            {   
                $rows[$i] = array('valtotmes' => 0,  'qtditem' => 0);//'descitem' => 'xxxxxx', 'valitem' => 0, array('descsubitem' =>'zzzzzz', 'valsubitem' => 1)));           
                $itensGeral = pg_query("SELECT * FROM item ORDER BY descitem " );
                $qtdItem = pg_query("SELECT COUNT(*) AS qtditem FROM item");
                $qtdItem = pg_fetch_array($qtdItem);
                $rows[$i]['qtditem'] = $qtdItem['qtditem']; 
                
                while($item = pg_fetch_array($itensGeral))
                {  
                    $rows[$i]['itens'][$item['descitem']] = array('valitem' => 0, 'qtdsubitem' => 0);
                    $subitensGeral = pg_query("SELECT * FROM subitem where coditem = ". $item['id']. " ORDER BY descsubitem " );
                    $qtdSubitem = pg_query("SELECT COUNT(*) AS qtdsubitem  FROM subitem where coditem = ". $item['id']);
                    $qtdSubitem = pg_fetch_array($qtdSubitem);
                    $rows[$i]['itens'][$item['descitem']]['qtdsubitem'] = $qtdSubitem['qtdsubitem']; 
                    
                    while($subitem = pg_fetch_array($subitensGeral))
                    {    
                        $rows[$i]['itens'][$item['descitem']]['subitens'][$subitem['descsubitem']] = 0;
                    }  
                }
            }
/*
            $itensGeral = pg_query("SELECT * FROM item ORDER BY descitem " );
            while($item = pg_fetch_array($itensGeral))
            { 
                $rowsTotais['valorGeralItem'][$item['descitem']] = 0;    
            }
*/
            $valorItemMeses =  pg_query("SELECT descitem, sum(valdespesa) as valor, EXTRACT(MONTH FROM dtdespesa) as mes FROM despesa, item  where item.id = coditem and EXTRACT(YEAR FROM dtdespesa) = ". $_GET["anoTab1"]. " group by ano, mes, descitem order by descitem" );
            while($valorItemMes = pg_fetch_array($valorItemMeses))
            {                      
                $rows[$valorItemMes['mes']]['itens'][$valorItemMes['descitem']]['valitem'] = $valorItemMes['valor']; 
            }    

            $valorSubitemMeses =  pg_query("SELECT descsubitem, descitem, sum(valdespesa) as valor, EXTRACT(MONTH FROM dtdespesa) as mes, EXTRACT(YEAR FROM dtdespesa) as ano FROM despesa, subitem, item where  item.id = despesa.coditem and item.id = subitem.coditem and despesa.codsubitem = subitem.id and EXTRACT(YEAR FROM dtdespesa) = ". $_GET["anoTab1"]. " group by ano, mes, descitem, descsubitem order by descsubitem" );
            while($valorSubitemMes = pg_fetch_array($valorSubitemMeses))
            {                      
                $rows[$valorSubitemMes['mes']]['itens'][$valorSubitemMes['descitem']]['subitens'][$valorSubitemMes['descsubitem']] = $valorSubitemMes['valor']; 
            }  
            
            $valorTotalMeses  =  pg_query("SELECT EXTRACT(MONTH FROM dtdespesa) as mes, sum(valdespesa) as valor FROM despesa  where  EXTRACT(YEAR FROM dtdespesa) = ". $_GET["anoTab1"]. " group by mes order by mes" );   
            while($valorTotalMes = pg_fetch_array($valorTotalMeses))
            {                      
                $rows[$valorTotalMes['mes']]['valtotmes'] = $valorTotalMes['valor']; 
            } 
            
            $valorTotalItens =  pg_query("SELECT descitem, sum(valdespesa) as valor FROM despesa, item  where item.id = coditem and EXTRACT(YEAR FROM dtdespesa) = ". $_GET["anoTab1"]. " group by descitem order by descitem" );               
            while($valorTotalItem = pg_fetch_array($valorTotalItens))
            {                      
               // $rows['valtotItem'][$valorTotalItem['descitem']] = $valorTotalItem['valor']; 
            }
                          
            $i = (int)$_GET["mesTab1"]; 
            
            if ($i > 0) {

                echo ' <table id=tabMensal class="footable" style="width: 500px;">
                           <thead >
                               <tr>                       
                                    <th rowspan = 2 >DESPESAS</th>
                                    <th rowspan = 2 >SUBITENS</th>                        
                                    <th>Valor</th><th>Total'.$i2.'</th>                                        
                               </tr>
                           </thead> 
                           <tfoot>
                                <tr>
                                    <th colspan = 2 rowspan = 2 >TOTAL DESPESAS</th>
                                    <th colspan = 2 >'.$rows[$i]['valtotmes'].'</th>  
                                </tr>
                            </tfoot>
                            <tbody >
                            <tr>';   
                
                $itens    = array_keys($rows[1]['itens']);               
                for($j=0; $j<count($itens);$j++) 
                {
                    $subitens = array_keys($rows[1]['itens'][$itens[$j]]['subitens']);
                    $linhas = count($subitens);
                    for($l=0; $l<$linhas;$l++) 
                    { 
                        if ($l == 0) 
                        {
                            echo '<td rowspan='.$linhas.'>'.$itens[$j].'</td><td>'.$subitens[$l].'</td>';
                            echo '<td>'.$rows[$i]['itens'][$itens[$j]]['subitens'][$subitens[$l]].'</td><td rowspan='.$linhas.'>'.$rows[$i]['itens'][$itens[$j]]['valitem'].'</td>';      
                        }    
                        else if ($l > 0)  
                        {      
                            echo '<tr><td>'.$subitens[$l].'</td>';
                            echo '<td>'.$rows[$i]['itens'][$itens[$j]]['subitens'][$subitens[$l]].'</td>';                            
                        }
                    } 
                    echo '</tr>';
                } 
                echo '</tbody> ';  
                echo '</table>';
            }        

            else if ($i == 0){

                $valorItemMeses =  pg_query("SELECT descitem, sum(valdespesa) as valor, EXTRACT(MONTH FROM dtdespesa) as mes FROM despesa, item  where item.id = coditem and EXTRACT(YEAR FROM dtdespesa) = ". $_GET["anoTab1"]. " group by mes, descitem order by descitem" );
                while($valorItemMes = pg_fetch_array($valorItemMeses))
                {                      
                    $rows[$valorItemMes['mes']]['itens'][$valorItemMes['descitem']]['valitem'] = $valorItemMes['valor']; 
                }    

                $valorSubitemMeses =  pg_query("SELECT descsubitem, descitem, sum(valdespesa) as valor, EXTRACT(MONTH FROM dtdespesa) as mes FROM despesa, subitem, item where  item.id = despesa.coditem and item.id = subitem.coditem and despesa.codsubitem = subitem.id and EXTRACT(YEAR FROM dtdespesa) = ". $_GET["anoTab1"]. " group by mes, descitem, descsubitem order by descsubitem" );
                while($valorSubitemMes = pg_fetch_array($valorSubitemMeses))
                {                      
                    $rows[$valorSubitemMes['mes']]['itens'][$valorSubitemMes['descitem']]['subitens'][$valorSubitemMes['descsubitem']] = $valorSubitemMes['valor']; 
                } 

                $valorTotalMeses  =  pg_query("SELECT EXTRACT(MONTH FROM dtdespesa) as mes, sum(valdespesa) as valor FROM despesa where  EXTRACT(YEAR FROM dtdespesa) = ". $_GET["anoTab1"]. " group by mes order by mes" );   
                while($valorTotalMes = pg_fetch_array($valorTotalMeses))
                {                      
                    $rows[$valorTotalMes['mes']]['valtotmes'] = $valorTotalMes['valor']; 
                } 

                $valorTotalItens =  pg_query("SELECT descitem, sum(valdespesa) as valor FROM despesa, item  where item.id = coditem and EXTRACT(YEAR FROM dtdespesa) = ". $_GET["anoTab1"]. "  group by descitem order by descitem" );             
                while($valorTotalItem = pg_fetch_array($valorTotalItens))
                {                      
                   // $rows['valtotItem'][$valorTotalItem['descitem']] = $valorTotalItem['valor']; 
                }


                echo ' <table id=tabGeral class="footable" >
                           <thead>
                               <tr>                       
                                    <th rowspan = 2 >DESPESAS</th><th rowspan = 2>SUBITENS</th>
                                    <th colspan = 2 >Janeiro</th>
                                    <th colspan = 2 >Fevereiro</th>
                                    <th colspan = 2 >Março</th>
                                    <th colspan = 2 >Abril</th>
                                    <th colspan = 2 >Maio</th>
                                    <th colspan = 2 >Junho</th>
                                    <th colspan = 2 >Julho</th>
                                    <th colspan = 2 >Agosto</th>
                                    <th colspan = 2 >Setembro</th>
                                    <th colspan = 2 >Outubro</th>
                                    <th colspan = 2 >Novembro</th>
                                    <th colspan = 2 >Dezembro</th>
                                    <th rowspan = 2 >Subitens</th><th rowspan = 2>DESPESAS</th>
                               </tr>';
                for($i=1; $i<=12;$i++)
                {
                    echo ' <th>Valor</th><th>Total</th>';
                }                     
                echo '  </thead> ';                    
                echo '  <tfoot> '; 
                echo '      <tr >
                                <th rowspan = 2  colspan = 2 >TOTAL DESPESA </th>'; 
                for($i=1; $i<=12;$i++)
                {
                    echo '      <th colspan = 2 >'.$rows[$i]['valtotmes'].'</th>'; 
                } 
                echo ' <th rowspan = 2  colspan = 2 >TOTAL DESPESA </th>'; 
                echo '     </tr>';
                echo '     <tr>   
                                <th colspan = 2 >Janeiro</th>
                                <th colspan = 2 >Fevereiro</th>
                                <th colspan = 2 >Março</th>
                                <th colspan = 2 >Abril</th>
                                <th colspan = 2 >Maio</th>
                                <th colspan = 2 >Junho</th>
                                <th colspan = 2 >Julho</th>
                                <th colspan = 2 >Agosto</th>
                                <th colspan = 2 >Setembro</th>
                                <th colspan = 2 >Outubro</th>
                                <th colspan = 2 >Novembro</th>
                                <th colspan = 2 >Dezembro</th>
                            </tr>
                        </tfoot>
                        <tbody >
                        <tr>';   
                $itens    = array_keys($rows[1]['itens']);               
                for($j=0; $j<count($itens);$j++) 
                {
                    $subitens = array_keys($rows[1]['itens'][$itens[$j]]['subitens']);
                    $linhas = count($subitens);
                    for($l=0; $l<$linhas;$l++) 
                    { 
                        if ($l == 0) 
                        {
                            echo '<td rowspan='.$linhas.'>'.$itens[$j].'</td><td>'.$subitens[$l].'</td>';
                            for($i=1; $i<=12;$i++)
                            {
                                echo '<td>'.$rows[$i]['itens'][$itens[$j]]['subitens'][$subitens[$l]].'</td><td rowspan='.$linhas.'>'.$rows[$i]['itens'][$itens[$j]]['valitem'].'</td>';                            
                            }
                            echo '<td>'.$subitens[$l].'</td><td rowspan='.$linhas.'>'.$itens[$j].'</td>';
                        }    
                        else if ($l > 0)  
                        {      
                            echo '<tr><td>'.$subitens[$l].'</td>';
                            for($i=1; $i<=12;$i++)
                            {
                                echo '<td>'.$rows[$i]['itens'][$itens[$j]]['subitens'][$subitens[$l]].'</td>';
                            }
                            echo '<td>'.$subitens[$l].'</td></tr>';
                        }
                    } 
                    echo '</tr>';
                } 
                echo '</tbody> ';        
                echo '</table>';
                //print json_encode($subitens);
                //print_r($rows);
            }
    }
    
    // 
    public function createAction()
    {
		//Insert record into database
		$result = pg_query("INSERT INTO despesa(coditem, codsubitem, valdespesa, dtdespesa) VALUES(".$_POST["coditem"].", ".$_POST["codsubitem"] .","  . $_POST["valdespesa"] . ", '". $_POST["dtdespesa"]."')");
                //$result = pg_query("INSERT INTO despesa(coditem, codsubitem, descdespesa, valdespesa, dtdespesa) VALUES(1,1,1,333,'2012-10-10')");
		
		//$result = mysql_query("INSERT INTO despesa(codsubitem, descdespesa, valdespesa, dtdespesa) VALUES(1 , 'teste', 12, 2012-03/26");
		//$result = mysql_query("INSERT INTO `zf2tutorial`.`despesa` (`id`, `codsubitem`, `descdespesa`, `valdespesa`, `dtdespesa`) VALUES (NULL, '1', 'teste', '14,00', '2013-03-25')");
		//Get last inserted record (to return to jTable)
		//$result = pg_query("SELECT * FROM despesa WHERE id = LAST_INSERT_ID();"); // só funciona no mysql esta select
               // $id = pg_query("SELECT currval('despesa_id_seq')");
                $result = pg_query("SELECT * FROM despesa WHERE id = currval('despesa_id_seq')"); //.$id);
		$row = pg_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
		print json_encode($jTableResult);        
    }

    // 
    public function updateAction()
    {
       // $data1 =  date('YY-MM-DD', strtotime('12/10/2012')); 
                 
		//Update record in database
		//$result = mysql_query("UPDATE despesa SET descdespesa = '" . $_POST["descdespesa"] . "', valdespesa = " . $_POST["valdespesa"] . ", codsubitem = " . $_POST["codsubitem"]. ", dtdespesa = " . date('yy-mm-dd',$_POST["dtdespesa"]). " WHERE id = " . $_POST["id"] . ";");
                $result = pg_query("UPDATE despesa SET  valdespesa = " . $_POST["valdespesa"] . ", coditem = " . $_POST["coditem"]. ", codsubitem = " . $_POST["codsubitem"]. ", dtdespesa = '" . $_POST["dtdespesa"] . "' WHERE id = " . $_POST["id"] . ";");
		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);        
    }

    // 
    public function deleteAction()
    {
		//Delete from database
		$result = pg_query("DELETE FROM despesa WHERE id = " . $_POST["id"] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);        
    }    

}
