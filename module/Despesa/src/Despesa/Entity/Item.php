<?php

namespace Despesa\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Um controle de despesas
 * 
 * @ORM\Entity
 * @ORM\Table(name="item")
 * @property string $descitem
 * @property int $id
 */


class Item 
{   

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $descitem;          
    
    /**
     * @ORM\OneToMany(targetEntity="Despesa\Entity\Subitem", mappedBy="item")
     */   
    protected $subitem = null; 
    
    
    public function __construct() {
        $this->subitem = new \Doctrine\Common\Collections\ArrayCollection();
    }   
    
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
