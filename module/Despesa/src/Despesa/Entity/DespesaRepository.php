<?php
// src/Despesa/Repository.php

use Doctrine\ORM\EntityRepository;

class DespesaRepository extends EntityRepository
{
    public function getRecentDespesa($number = 30)
    {
        $dql = "SELECT b, e, r FROM Bug b JOIN b.engineer e JOIN b.reporter r ORDER BY b.created DESC";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($number);
        return $query->getResult();
    }

}
