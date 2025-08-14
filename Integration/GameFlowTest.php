<?php

use PHPUnit\Framework\TestCase;
use Morpion\{Game, Board, Rules, Player, Position};

final class GameFlowTest extends TestCase
{
    public function testXWinsTopRowEndToEnd(): void
    {
        // X 0,0 | O 1,0 | X 0,1 | O 1,1 | X 0,2  => X gagne (ligne du haut)
        $g = new Game();
        $g = $g->play(new Position(0,0)); // X
        $g = $g->play(new Position(1,0)); // O
        $g = $g->play(new Position(0,1)); // X
        $g = $g->play(new Position(1,1)); // O
        $g = $g->play(new Position(0,2)); // X

        $this->assertSame(Player::X, Rules::winner($g->board()));
        $this->assertSame('Gagnant: X', $g->status());
    }

    public function testFullDrawSequenceEndToEnd(): void
    {
        $g = new Game();

        // Séquence validée : pas de victoire avant le dernier coup, et nul à la fin
        $moves = [
            [0,0], // X
            [0,1], // O
            [0,2], // X
            [1,0], // O
            [1,1], // X
            [2,0], // O
            [1,2], // X
            [2,2], // O
            [2,1], // X
        ];

        foreach ($moves as $i => [$r, $c]) {
            $g = $g->play(new Position($r, $c));
            // Securité : aucune victoire ne doit apparaître avant le 9e coup
            if ($i < 8) {
                $this->assertNull(Rules::winner($g->board()), "Victoire prématurée au coup #$i ($r,$c).");
            }
        }

        $this->assertNull(Rules::winner($g->board()));
        $this->assertTrue(Rules::isDraw($g->board()));
        $this->assertSame('Match nul', $g->status());

        // Optionnel : verrouille la position finale attendue
        $this->assertSame("XOX\nOXX\nOXO", (string)$g->board());
    }

    public function testCannotPlayAfterGameIsOverEndToEnd(): void
    {
        // Partie gagnée par X sur la première ligne, puis tentative de jouer encore => exception
        $g = new Game();
        $g = $g->play(new Position(0,0)); // X
        $g = $g->play(new Position(1,0)); // O
        $g = $g->play(new Position(0,1)); // X
        $g = $g->play(new Position(1,1)); // O
        $g = $g->play(new Position(0,2)); // X gagne

        $this->assertSame(Player::X, Rules::winner($g->board()));

        $this->expectException(RuntimeException::class);
        $g->play(new Position(2,2)); // interdit: partie déjà terminée
    }
}
