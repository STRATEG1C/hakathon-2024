<?php

final class GameMap
{
    private const PLAYER_INDEX = 0;
    private const PLAYER_DIRECTION_INDEX = 1;
    private const ENEMY_INDEX = 0;
    private const ENEMY_DIRECTION_INDEX = 1;

    private const X = 1;
    private const Y = 0;

    private $map = [];
    private $asteroidsPositions = [];
    private $playerPosition = [0, 0];
    private $playerDirection = '-';
    private $coinsPositions = [];
    private $enemyPositions = [];

    public function __construct(array $map)
    {
        $this->map = $map;

        foreach ($map as $rowIndex => $row) {
            foreach ($row as $columnIndex => $cell) {
                if ($cell === 'A') {
                    $this->asteroidsPositions[] = [$rowIndex, $columnIndex];
                } else if ($cell[self::PLAYER_INDEX] === 'P') {
                    $this->playerPosition = [$rowIndex, $columnIndex];
                    $this->playerDirection = $cell[self::PLAYER_DIRECTION_INDEX];
                } else if ($cell[self::ENEMY_INDEX] === 'E') {
                    $enemy = [$rowIndex, $columnIndex];
                    $enemyPosition[self::ENEMY_DIRECTION_INDEX] = $cell[self::ENEMY_DIRECTION_INDEX];
                    $this->enemyPositions[] = [$enemy, $enemyPosition];
                } else if ($cell === 'C') {
                    $this->coinsPositions[] = [$rowIndex, $columnIndex];
                } else if ($cell === '_') {
                    continue;
                }
            }
        }
    }

    private function findShortestPath(array $start, array $end): ?array
    {
        $rows = count($this->map);
        $cols = count($this->map[0]);
        $directions = [[-1, 0], [1, 0], [0, -1], [0, 1]];

        $queue = [[$start]];
        $visited = [$start];

        while (!empty($queue)) {
            $path = array_shift($queue);

            $current = end($path);

            if ($current === $end) {
                return $path;
            }

            foreach ($directions as $direction) {
                $newRow = $current[0] + $direction[0];
                $newCol = $current[1] + $direction[1];

                $next = [$newRow, $newCol];

                if ($newRow >= 0 && $newRow < $rows &&
                    $newCol >= 0 && $newCol < $cols &&
                    !in_array($next, $visited) &&
                    $this->map[$newRow][$newCol] !== 'A') {

                    $visited[] = $next;
                    $queue[] = array_merge($path, [$next]);
                }
            }
        }

        return null;
    }

    public function getPlayerPosition(): array
    {
        return $this->playerPosition;
    }

    public function getPlayerDirection(): string
    {
        return $this->playerDirection;
    }

    public function findPathsToCoins(): array
    {
        $paths = [];
        foreach ($this->coinsPositions as $coinPosition) {
            $path = $this->findShortestPath($this->playerPosition, $coinPosition);
            if ($path !== null) {
                $paths[] = [
                    'path' => $path,
                    'length' => count($path) - 1,
                ];
            }
        }
        return $paths;
    }

    public function findPathsToEnemies(): array
    {
        $paths = [];
        foreach ($this->enemyPositions as $enemy) {
            $path = $this->findShortestPath($this->playerPosition, $enemy[0]);
            if ($path !== null) {
                $paths[] = [
                    'path' => $path,
                    'length' => count($path) - 1,
                ];
            }
        }
        return $paths;
    }

    public function isFire(): bool
    {
        $playerPosition = $this->getPlayerPosition();
        $playerDirection = $this->getPlayerDirection();

        if (
            $playerDirection === 'S'
        ) {
            for ($i = 1; $i <= 4; $i++) {
                if (
                    isset($this->map[$playerPosition[0]+$i][$playerPosition[1]]) &&
                    $this->map[$playerPosition[0]+$i][$playerPosition[1]][0] === 'E'
                ) {
                    return true;
                }
            }
        }

        if (
            $playerDirection === 'N'
        ) {
            for ($i = 1; $i <= 4; $i++) {
                if (
                    isset($this->map[$playerPosition[0]-$i][$playerPosition[1]]) &&
                    $this->map[$playerPosition[0]-$i][$playerPosition[1]][0] === 'E'
                ) {
                    return true;
                }
            }
        }

        if (
            $playerDirection === 'W'
        ) {
            for ($i = 1; $i <= 4; $i++) {
                if (
                    isset($this->map[$playerPosition[0]][$playerPosition[1]-$i]) &&
                    $this->map[$playerPosition[0]][$playerPosition[1]-$i][0] === 'E'
                ) {
                    return true;
                }
            }
        }

        if (
            $playerDirection === 'E'
        ) {
            for ($i = 1; $i <= 4; $i++) {
                if (
                    isset($this->map[$playerPosition[0]][$playerPosition[1]+$i]) &&
                    $this->map[$playerPosition[0]][$playerPosition[1]+$i][0] === 'E'
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    public function nextMove(): ?string
    {
        $coins = $this->findPathsToCoins();
        $enemies = $this->findPathsToEnemies();

        if (empty($coins) && empty($enemies)) {
            return null;
        }

        $enemiesAndCoins = array_merge($coins, $enemies);

        $length = 99;
        $position = null;
        foreach ($enemiesAndCoins as $item) {
            if ($item['length'] < $length) {
                $length = $item['length'];
                $position = $item['path'][1];
            }
        }

        return $this->getStep($position);
    }

    private function movePlayer(string $direction): ?string {
        $playerDirection = $this->getPlayerDirection();

//        if ($playerDirection === 'N' && $direction === 'E') {
//            return 'R';
//        } else if ($playerDirection === 'N' && $direction === 'W' ) {
//            return 'L';
//        } else if ($playerDirection === 'N' && $direction === 'N') {
//            return 'M';
//        } else if ($playerDirection === 'N' && $direction === 'S') {
//            return 'R';
//        } else if ($playerDirection === 'S' && $direction === 'E') {
//            return 'R';
//        } else if ($playerDirection === 'S' && $direction === 'W') {
//            return 'L';
//        } else if ($playerDirection === 'S' && $direction === 'N') {
//            return 'R';
//        } else if ($playerDirection === 'S' && $direction === 'S') {
//            return 'M';
//        } else if ($playerDirection === 'E' && $direction === 'E') {
//            return 'M';
//        } else if ($playerDirection === 'E' && $direction === 'W') {
//            return 'R';
//        } else if ($playerDirection === 'E' && $direction === 'N') {
//            return 'L';
//        } else if ($playerDirection === 'E' && $direction === 'S') {
//            return 'R';
//        } else if ($playerDirection === 'W' && $direction === 'E') {
//            return 'R';
//        } else if ($playerDirection === 'W' && $direction === 'W') {
//            return 'M';
//        } else if ($playerDirection === 'W' && $direction === 'N') {
//            return 'R';
//        } else if ($playerDirection === 'W' && $direction === 'S') {
//            return 'L';
//        }

        $crossingArray = [
            'SW' => 'R',
            'SE' => 'L',
            'SN' => 'L',

            'NW' => 'L',
            'NE' => 'R',
            'NS' => 'L',

            'WN' => 'R',
            'WS' => 'L',
            'WE' => 'L',

            'EN' => 'L',
            'ES' => 'R',
            'EW' => 'L'
        ];

        return $crossingArray[$playerDirection . $direction] ?? 'M';
    }

    private function getStep(array $nextPoint): ?string {
        $position = $this->getPlayerPosition();

        if ($nextPoint[self::X] > $position[self::X]) {
            return $this->movePlayer('E');
        } else if ($nextPoint[self::X] < $position[self::X]) {
            return $this->movePlayer('W');
        } else if ($nextPoint[self::Y] > $position[self::Y]) {
            return $this->movePlayer('S');
        } else {
            return $this->movePlayer('N');
        }
    }

}
