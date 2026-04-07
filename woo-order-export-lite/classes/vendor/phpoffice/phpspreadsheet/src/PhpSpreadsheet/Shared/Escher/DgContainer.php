<?php

namespace WOE\PhpOffice\PhpSpreadsheet\Shared\Escher;

use WOE\PhpOffice\PhpSpreadsheet\Exception as SpreadsheetException;
class DgContainer
{
    /**
     * Drawing index, 1-based.
     *
     * @var ?int
     */
    private $dgId;
    /**
     * Last shape index in this drawing.
     *
     * @var ?int
     */
    private $lastSpId;
    /** @var ?DgContainer\SpgrContainer */
    private $spgrContainer;
    public function getDgId(): ?int
    {
        return $this->dgId;
    }
    public function setDgId(int $value): void
    {
        $this->dgId = $value;
    }
    public function getLastSpId(): ?int
    {
        return $this->lastSpId;
    }
    public function setLastSpId(int $value): void
    {
        $this->lastSpId = $value;
    }
    public function getSpgrContainer(): ?\WOE\PhpOffice\PhpSpreadsheet\Shared\Escher\DgContainer\SpgrContainer
    {
        return $this->spgrContainer;
    }
    public function getSpgrContainerOrThrow(): \WOE\PhpOffice\PhpSpreadsheet\Shared\Escher\DgContainer\SpgrContainer
    {
        if ($this->spgrContainer !== null) {
            return $this->spgrContainer;
        }
        throw new SpreadsheetException('spgrContainer is unexpectedly null');
    }
    /** @param DgContainer\SpgrContainer $spgrContainer */
    public function setSpgrContainer($spgrContainer): \WOE\PhpOffice\PhpSpreadsheet\Shared\Escher\DgContainer\SpgrContainer
    {
        return $this->spgrContainer = $spgrContainer;
    }
}