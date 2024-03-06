<?php

declare(strict_types=1);

namespace SqlModels;

class ColumnInfo
{


    /**
     * @param    string        $name
     * @param    string        $type
     * @param    bool          $nullable
     */
    public function __construct(public string $name = '', public string $type = '', public bool $nullable = true)
    {
    } //end __construct()


}//end class
