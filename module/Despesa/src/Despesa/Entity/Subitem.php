<?php

namespace Despesa\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


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
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="subitem")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     * @var item
     **/
    protected $item; 
    
    
    public function __construct() {
        $this->item = new \Doctrine\Common\Collections\ArrayCollection();
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
