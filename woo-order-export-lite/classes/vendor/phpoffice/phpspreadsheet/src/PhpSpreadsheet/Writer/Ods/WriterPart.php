<?php

namespace WOE\PhpOffice\PhpSpreadsheet\Writer\Ods;

use WOE\PhpOffice\PhpSpreadsheet\Writer\Ods;

abstract class WriterPart
{
    /**
     * Parent Ods object.
     */
    private Ods $parentWriter;

    /**
     * Get Ods writer.
     */
    public function getParentWriter(): Ods
    {
        return $this->parentWriter;
    }

    /**
     * Set parent Ods writer.
     */
    public function __construct(Ods $writer)
    {
        $this->parentWriter = $writer;
    }

    abstract public function write(): string;
}
