<?php

namespace Dotmailer\Entity;

final class StandardCampaign extends Campaign
{
    /**
     * @inheritdoc
     */
    public function isSplitTest(): bool
    {
        return false;
    }
}
