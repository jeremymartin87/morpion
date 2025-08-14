<?php
namespace Morpion;

final class Game
{
    public function __construct(
        private Board $board = new Board(),
        private Player $turn = Player::X
    ) {}

    public function board(): Board { return $this->board; }
    public function turn(): Player { return $this->turn; }

    public function play(Position $p): self
    {
        Rules::ensurePlayable($this->board);
        $nextBoard = $this->board->withMove($p, $this->turn);
        return new self($nextBoard, $this->turn->other());
    }

    public function status(): string
    {
        $w = Rules::winner($this->board);
        if ($w !== null) return "Gagnant: {$w->value}";
        if (Rules::isDraw($this->board)) return "Match nul";
        return "Au tour de: {$this->turn->value}";
    }
}
