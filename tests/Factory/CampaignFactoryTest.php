<?php

namespace Dotmailer\Factory;

use Dotmailer\Entity\Campaign;
use Dotmailer\Entity\SplitTestCampaign;
use Dotmailer\Entity\StandardCampaign;
use PHPUnit\Framework\TestCase;

class CampaignFactoryTest extends TestCase
{
    const CAMPAIGN_DATA = [
        'id' => 123,
        'name' => 'name',
        'subject' => 'subject',
        'fromName' => 'from name',
        'htmlContent' => '<p>content</p>',
        'plainTextContent' => 'content',
        'fromAddress' => [
            'id' => 456,
            'email' => 'from@email.address',
        ],
        'replyAction' => Campaign::REPLY_ACTION_UNSET,
        'replyToAddress' => 'reply@email.address',
        'status' => Campaign::STATUS_UNSENT
    ];

    public function testBuildStandardCampaign()
    {
        $campaignArray = array_merge(self::CAMPAIGN_DATA, ['isSplitTest' => false]);
        $campaign = CampaignFactory::build($this->arrayToObject($campaignArray));

        $this->assertInstanceOf(StandardCampaign::class, $campaign);
        $this->assertEquals($campaignArray, $campaign->asArray());
    }

    public function testBuildSplitTestCampaign()
    {
        $campaignArray = array_merge(self::CAMPAIGN_DATA, ['isSplitTest' => true]);
        $campaign = CampaignFactory::build($this->arrayToObject($campaignArray));

        $this->assertInstanceOf(SplitTestCampaign::class, $campaign);
        $this->assertEquals($campaignArray, $campaign->asArray());
    }

    /**
     * @param array $data
     *
     * @return object
     */
    private function arrayToObject(array $data)
    {
        return json_decode(json_encode($data));
    }
}
