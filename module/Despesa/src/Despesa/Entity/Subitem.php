<?php

namespace Despesa\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Despesa\Entity\Item;

/**
 * Um controle de despesas
 * 
 * @ORM\Entity
 * @ORM\Table(name="subitem")
 * @property string $descsubitem
 * @property int $id
 */


class Subitem 
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
    protected $descsubitem;      
    
    /**
     *@ORM\ManyToOne(targetEntity="Despesa\Entity\Item", inversedBy="subitem")
     *@ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    protected $item = null; 
    
    /**
     * @ORM\OneToMany(targetEntity="Despesa\Entity\Despesa", mappedBy="subitem")
     */
   protected $despesa = null;

    public function __construct()
    {
        $this->despesa = new ArrayCollection();
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
