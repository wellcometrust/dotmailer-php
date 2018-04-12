<?php

namespace Dotmailer\Entity;

final class SplitTestCampaign extends Campaign
{
    /**
     * @inheritdoc
     */
    public function isSplitTest(): bool
    {
        return true;
    }
}
