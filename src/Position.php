<?php
namespace Morpion;

final class Position
{
    public function __construct(
        public readonly int $row,
        public readonly int $col
    ) {
        if ($row < 0 || $row > 2 || $col < 0 || $col > 2) {
            throw new \OutOfBoundsException("Position invalide ($row,$col).");
        }
    }
}
