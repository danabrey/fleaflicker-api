<?php
namespace DanAbrey\FleaflickerApi\Models;

final class FleaflickerLeagueRosterPosition
{
    public string $label;
    public string $group;
    public array $eligibility;
    public int $min;
    public int $max;
    public int $start;
}
