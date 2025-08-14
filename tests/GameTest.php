<?php
use PHPUnit\Framework\TestCase;
use Morpion\{Game, Position, Rules, Player, Board};

final class GameTest extends TestCase
{
    public function testTurnAlternationAndInvalidMoveNonTrivial(): void
    {
        $g = new Game(); // X commence
        $this->assertSame("Au tour de: X", $g->status());

        // X joue centre
        $g = $g->play(new Position(1,1));
        $this->assertSame("Au tour de: O", $g->status());

        // O tente de rejouer sur la même case -> doit lever une exception
        $this->expectException(RuntimeException::class);
        $g->play(new Position(1,1));
    }

    public function testWinSequenceAntiDiagonal(): void
    {
        $g = new Game(new Board(), Player::X);
        // X (0,2)
        $g = $g->play(new Position(0,2));
        // O (0,0)
        $g = $g->play(new Position(0,0));
        // X (1,1)
        $g = $g->play(new Position(1,1));
        // O (1,0)
        $g = $g->play(new Position(1,0));
        // X (2,0) -> X gagne par anti-diagonale
        $g = $g->play(new Position(2,0));

        $this->assertSame(Player::X, Rules::winner($g->board()));
        $this->assertStringStartsWith("Gagnant: X", $g->status());
    }

    public function testCannotPlayAfterGameIsOverNonTrivial(): void
    {
        // Ligne gagnante en colonne 2 pour O
        $b = Board::fromString(".X./.X./.X.");
        // C'est à O (peu importe), la partie est déjà gagnée (colonne 1 = X) -> ensurePlayable cassera
        $g = new Game($b, Player::O);
        $this->assertSame(Player::X, Rules::winner($g->board()));

        $this->expectException(RuntimeException::class);
        $g->play(new Position(0,0));
    }

    public function testFullDrawGameSequenceNonTrivial(): void
    {
        $g = new Game();
        // X: (1,1)
        $g = $g->play(new Position(1,1));
        // O: (0,0)
        $g = $g->play(new Position(0,0));
        // X: (0,2)
        $g = $g->play(new Position(0,2));
        // O: (0,1)
        $g = $g->play(new Position(0,1));
        // X: (2,1)
        $g = $g->play(new Position(2,1));
        // O: (1,2)
        $g = $g->play(new Position(1,2));
        // X: (1,0)
        $g = $g->play(new Position(1,0));
        // O: (2,0)
        $g = $g->play(new Position(2,0));
        // X: (2,2) -> nul
        $g = $g->play(new Position(2,2));

        $this->assertNull(Rules::winner($g->board()));
        $this->assertTrue(Rules::isDraw($g->board()));
        $this->assertSame("Match nul", $g->status());
    }
}
