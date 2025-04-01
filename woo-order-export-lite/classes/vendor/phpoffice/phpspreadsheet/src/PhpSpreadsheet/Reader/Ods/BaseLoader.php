<?php

namespace WOE\PhpOffice\PhpSpreadsheet\Reader\Ods;

use DOMElement;
use WOE\PhpOffice\PhpSpreadsheet\Spreadsheet;

abstract class BaseLoader
{
    protected Spreadsheet $spreadsheet;

    protected string $tableNs;

    public function __construct(Spreadsheet $spreadsheet, string $tableNs)
    {
        $this->spreadsheet = $spreadsheet;
        $this->tableNs = $tableNs;
    }

    abstract public function read(DOMElement $workbookData): void;
}
