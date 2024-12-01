<?php

namespace App\Services\Statistic\Model;

class TableResult {

    /**
     * @param list<string> $header
     * @param list<list<string>> $result
     */
    public function __construct(
        protected readonly array $header,
        protected readonly array $result
    ) {
    }

    /**
     * @return list<string>
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @return list<list<string>>
     */
    public function getResult(): array
    {
        return $this->result;
    }
}
