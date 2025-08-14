<?php
namespace Morpion;

enum Player: string
{
    case X = 'X';
    case O = 'O';

    public function other(): self
    {
        return $this === self::X ? self::O : self::X;
    }
}
