<?php

namespace AppBundle\Stat\Form;

use AppBundle\Service\FormService;
use Ds\Component\Model\Attribute;
use Ds\Component\Statistic\Model\Datum;
use Ds\Component\Statistic\Stat\Stat;

/**
 * Class CountStat
 */
class CountStat implements Stat
{
    use Attribute\Alias;

    /**
     * @var \AppBundle\Service\FormService
     */
    protected $formService;

    /**
     * Constructor
     *
     * @param \AppBundle\Service\FormService $formService
     */
    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $datum = new Datum;
        $datum
            ->setAlias($this->alias)
            ->setValue($this->formService->getRepository()->getCount([]));

        return $datum;
    }
}
