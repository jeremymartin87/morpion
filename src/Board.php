<?php
namespace Morpion;

final class Board
{
    /** @var array<int,array<int,Player|null>> */
    private array $grid;

    public function __construct(?array $grid = null)
    {
        $this->grid = $grid ?? array_fill(0, 3, array_fill(0, 3, null));
    }

    public static function fromString(string $pattern): self
    {
        // Ex: "X.O/.../..X" (3 lignes séparées par '/'; '.' = vide)
        $rows = explode('/', $pattern);
        if (count($rows) !== 3) {
            throw new \InvalidArgumentException("Pattern invalide: doit contenir 3 lignes.");
        }
        $grid = [];
        foreach ($rows as $r => $row) {
            if (strlen($row) !== 3) {
                throw new \InvalidArgumentException("Ligne $r invalide: longueur ≠ 3.");
            }
            $grid[$r] = [];
            for ($c = 0; $c < 3; $c++) {
                $ch = $row[$c];
                $grid[$r][$c] = $ch === '.'
                    ? null
                    : Player::from($ch);
            }
        }
        return new self($grid);
    }

    public function get(int $row, int $col): ?Player
    {
        return $this->grid[$row][$col];
    }

    public function isEmpty(Position $p): bool
    {
        return $this->get($p->row, $p->col) === null;
    }

    public function withMove(Position $p, Player $player): self
    {
        if (!$this->isEmpty($p)) {
            throw new \RuntimeException("Case déjà occupée en ($p->row,$p->col).");
        }
        $copy = $this->grid;
        $copy[$p->row][$p->col] = $player;
        return new self($copy);
    }

    /** @return Position[] */
    public function empties(): array
    {
        $out = [];
        for ($r = 0; $r < 3; $r++) {
            for ($c = 0; $c < 3; $c++) {
                if ($this->grid[$r][$c] === null) {
                    $out[] = new Position($r, $c);
                }
            }
        }
        return $out;
    }

    public function __toString(): string
    {
        $s = '';
        for ($r = 0; $r < 3; $r++) {
            for ($c = 0; $c < 3; $c++) {
                $cell = $this->grid[$r][$c];
                $s .= $cell?->value ?? '.';
            }
            if ($r < 2) { $s .= PHP_EOL; }
        }
        return $s;
    }
}
