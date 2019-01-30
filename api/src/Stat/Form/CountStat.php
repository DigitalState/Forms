<?php

namespace App\Stat\Form;

use App\Service\FormService;
use Ds\Component\Model\Attribute;
use Ds\Component\Statistic\Model\Datum;
use Ds\Component\Statistic\Stat\Stat;

/**
 * Class CountStat
 */
final class CountStat implements Stat
{
    use Attribute\Alias;

    /**
     * @var \App\Service\FormService
     */
    private $formService;

    /**
     * Constructor
     *
     * @param \App\Service\FormService $formService
     */
    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): Datum
    {
        $datum = new Datum;
        $datum
            ->setAlias($this->alias)
            ->setValue($this->formService->getRepository()->getCount([]));

        return $datum;
    }
}
