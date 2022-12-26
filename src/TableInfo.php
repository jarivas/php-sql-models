<?php

declare(strict_types=1);

namespace SqlModels;

class TableInfo
{
    public function __construct(public string $name, public array $columns){}
}