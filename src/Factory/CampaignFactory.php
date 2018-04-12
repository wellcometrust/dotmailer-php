<?php

namespace Dotmailer\Factory;

use Dotmailer\Entity\Address;
use Dotmailer\Entity\Campaign;
use Dotmailer\Entity\SplitTestCampaign;
use Dotmailer\Entity\StandardCampaign;

class CampaignFactory
{
    /**
     * @param object $campaign
     *
     * @return Campaign
     */
    public static function build($campaign): Campaign
    {
        $campaignType = $campaign->isSplitTest
            ? SplitTestCampaign::class
            : StandardCampaign::class;

        return new $campaignType(
            $campaign->id,
            $campaign->name,
            $campaign->subject,
            $campaign->fromName,
            $campaign->htmlContent,
            $campaign->plainTextContent,
            new Address(
                $campaign->fromAddress->id,
                $campaign->fromAddress->email
            ),
            $campaign->replyAction,
            $campaign->replyToAddress ?? '',
            $campaign->status
        );
    }
}
