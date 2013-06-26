<?php

namespace Despesa\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Um controle de despesas
 * 
 * @ORM\Entity
 * @ORM\Table(name="despesa")
 * @property string $descdespesa
 * @property decimal $valdespesa
 * @property datetime $dtdespesa
 * @property int $id * 
 */
class Despesa 
{   

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $dtdespesa;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $valdespesa;

    /**
     * @ORM\Column(type="string")
     */
    protected $descdespesa;
       
    /**
     * @ORM\ManyToOne(targetEntity="Subitem", inversedBy="despesa")
     * @ORM\JoinColumn(name="subitem_id", referencedColumnName="id")
     */
    protected $subitem;    
    
    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property) 
    {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) 
    {
        $this->$property = $value;
    }   
   
    
}
