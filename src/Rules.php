<?php
namespace Morpion;

final class Rules
{
    public static function winner(Board $b): ?Player
    {
        $lines = [
            // rows
            [[0,0],[0,1],[0,2]],
            [[1,0],[1,1],[1,2]],
            [[2,0],[2,1],[2,2]],
            // cols
            [[0,0],[1,0],[2,0]],
            [[0,1],[1,1],[2,1]],
            [[0,2],[1,2],[2,2]],
            // diags
            [[0,0],[1,1],[2,2]],
            [[0,2],[1,1],[2,0]],
        ];
        foreach ($lines as $line) {
            [$a,$b1,$c] = $line;
            $pa = $b->get($a[0], $a[1]);
            if ($pa === null) continue;
            if (
                $pa === $b->get($b1[0], $b1[1]) &&
                $pa === $b->get($c[0], $c[1])
            ) {
                return $pa;
            }
        }
        return null;
    }

    public static function isDraw(Board $b): bool
    {
        return self::winner($b) === null && count($b->empties()) === 0;
    }

    public static function ensurePlayable(Board $b): void
    {
        if (self::winner($b) !== null) {
            throw new \RuntimeException("La partie est déjà terminée.");
        }
    }
}
