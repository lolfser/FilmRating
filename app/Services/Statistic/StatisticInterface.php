<?php

namespace App\Services\Statistic;

use App\Services\Statistic\Model\TableResult;

interface StatisticInterface {
    public function receive(array $options = []): TableResult;
}
