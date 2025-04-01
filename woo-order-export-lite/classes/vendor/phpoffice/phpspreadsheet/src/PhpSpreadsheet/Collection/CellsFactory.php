<?php

namespace WOE\PhpOffice\PhpSpreadsheet\Collection;

use WOE\PhpOffice\PhpSpreadsheet\Settings;
use WOE\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class CellsFactory
{
    /**
     * Initialise the cache storage.
     *
     * @param Worksheet $worksheet Enable cell caching for this worksheet
     *
     * */
    public static function getInstance(Worksheet $worksheet): Cells
    {
        return new Cells($worksheet, Settings::getCache());
    }
}
