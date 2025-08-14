<?php
use PHPUnit\Framework\TestCase;
use Morpion\{Board, Player, Position};

final class BoardTest extends TestCase
{
    public function testFromStringBuildsCorrectGrid(): void
    {
        $b = Board::fromString("X.O/..X/O..");
        $this->assertSame(Player::X, $b->get(0,0));
        $this->assertSame(null, $b->get(1,1));
        $this->assertSame(Player::O, $b->get(2,0));
        $this->assertSame(Player::X, $b->get(1,2));
    }

    public function testWithMoveThrowsOnOccupiedCell(): void
    {
        $b = Board::fromString("X../.../...");
        $this->expectException(RuntimeException::class);
        $b->withMove(new Position(0,0), Player::O);
    }

    public function testPositionOutOfBounds(): void
    {
        $this->expectException(OutOfBoundsException::class);
        new Position(3, 0);
    }
}
