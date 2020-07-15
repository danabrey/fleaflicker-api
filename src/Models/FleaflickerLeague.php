<?php
namespace DanAbrey\FleaflickerApi\Models;

final class FleaflickerLeague
{
    public int $id;
    public string $name;
    /**
     * @var FleaflickerLeagueRosterRequirements
     */
    public FleaflickerLeagueRosterRequirements $rosterRequirements;
    /**
     * @var FleaflickerTeam
     */
    public FleaflickerTeam $ownedTeam;
}
