<?php
namespace DanAbrey\FleaflickerApi\Models;

final class FleaflickerRoster
{
    /**
     * @var FleaflickerTeam
     */
    public FleaflickerTeam $team;
    /**
     * @var FleaflickerPlayer[]
     */
    public array $players = [];
}
