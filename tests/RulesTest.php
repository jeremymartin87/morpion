<?php
use PHPUnit\Framework\TestCase;
use Morpion\{Board, Player, Rules};

final class RulesTest extends TestCase
{
    public function testWinnerOnAntiDiagonalNonTrivial(): void
    {
        // . X X
        // O X .
        // X . O
        // Anti-diagonale (0,2)-(1,1)-(2,0) de X : victoire X
        $b = Board::fromString(".XX/OX./X.O");
        $this->assertSame(Player::X, Rules::winner($b));
    }

    public function testDrawOnLastMoveNonTrivial(): void
    {
        // Configuration menant à nul au dernier coup : aucun 3-en-ligne
        $b = Board::fromString("XOX/OXO/OX.");
        // Dernière case (2,2) = X -> nul (pas de gagnant)
        $b = new Board([
            [Player::X, Player::O, Player::X],
            [Player::O, Player::X, Player::O],
            [Player::O, Player::X, null],
        ]);
        $this->assertNull(Rules::winner($b));
        $this->assertFalse(Rules::isDraw($b)); // pas encore nul
        // joue dernière case avec X
        $b2 = new Board([
            [Player::X, Player::O, Player::X],
            [Player::O, Player::X, Player::O],
            [Player::O, Player::X, Player::O],
        ]);
        $this->assertNull(Rules::winner($b2));
        $this->assertTrue(Rules::isDraw($b2));
    }

    public function testEnsurePlayablePreventsMoveAfterWin(): void
    {
        $b = Board::fromString("XXX/.../...");
        $this->expectException(RuntimeException::class);
        Rules::ensurePlayable($b);
    }
}
