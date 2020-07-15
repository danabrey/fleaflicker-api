<?php
namespace DanAbrey\FleaflickerApi\Models;

final class FleaflickerTeam
{
    public int $id;
    public string $name;
    /**
     * @var FleaflickerPlayer[]
     */
    public array $players = [];
}
