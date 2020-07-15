<?php
namespace DanAbrey\FleaflickerApi\Models;

final class FleaflickerProPlayer
{
    public int $id;
    public string $nameFull;
    public string $nameShort;
    public string $position;
    /**
     * @var FleaflickerProPlayerExternalId[]
     */
    public array $externalIds = [];
}
