<?php
namespace DanAbrey\FleaflickerApi\Models;

final class FleaflickerDraftPick
{
    public int $season;
    /**
     * @var FleaflickerDraftPickSlot
     */
    public FleaflickerDraftPickSlot $slot;
    /**
     * The team that currently owns the pick
     * @var FleaflickerTeam
     */
    public FleaflickerTeam $ownedBy;
    /**
     * Only present when the pick has been traded
     * @var FleaflickerTeam|null
     */
    public ?FleaflickerTeam $originalOwner = null;
    public bool $traded = false;
    /**
     * True when the team the pick was fetched for originally owned it but has traded it away
     */
    public bool $lost = false;
}
