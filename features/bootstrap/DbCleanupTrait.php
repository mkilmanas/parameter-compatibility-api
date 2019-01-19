<?php
declare(strict_types=1);


use Doctrine\ORM\EntityManager;

trait DbCleanupTrait
{
    abstract protected function getEntityManager() : EntityManager;

    /**
     * @BeforeScenario
     */
    public function initTransaction()
    {
        $this->getEntityManager()->getConnection()->beginTransaction();
    }

    /**
     * @AfterScenario
     */
    public function rollbackTransaction()
    {
        $this->getEntityManager()->getConnection()->rollBack();
    }
}