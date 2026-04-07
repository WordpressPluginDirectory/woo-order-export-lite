<?php

namespace WOE\PhpOffice\PhpSpreadsheet\RichText;

interface ITextElement
{
    /**
     * Get text.
     *
     * @return string Text
     */
    public function getText();

    /**
     * Set text.
     *
     * @param string $text Text
     *
     * @return ITextElement
     */
    public function setText($text);

    /**
     * Get font.
     *
     * @return null|\WOE\PhpOffice\PhpSpreadsheet\Style\Font
     */
    public function getFont();

    /**
     * Get hash code.
     *
     * @return string Hash code
     */
    public function getHashCode();
}
