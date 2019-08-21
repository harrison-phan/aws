<?php

namespace Si6\Aws\Classes\Pinpoint;


use Exception;
use Illuminate\Support\Carbon;

/**
 * Refer Document in https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-pinpoint-2016-12-01.html#createcampaign
 */
class Campaign extends AbstractPinpoint
{
    /** @var array */
    protected $schedule = [];

    /** @var array */
    protected $additional = [];

    /** @var string */
    protected $description = null;

    /** @var integer */
    protected $holdoutPercent = 0;

    /** @var array */
    protected $messageConfiguration = [];

    /** @var limit */
    protected $limits = [];

    /** @var array */
    protected $hook = [];

    /** @var array */
    protected $segmentInfo = [
        'id' => null,
        'version' => null
    ];

    /** @var array */
    protected $treatment = [
        'name' => null,
        'description' => null
    ];

    /**
     * Set Message Configuration
     *
     * @param array $options
     * @return $this
     */
    public function setMessageConfiguration(array $options)
    {
        $this->messageConfiguration = $options;

        return $this;
    }

    /**
     * Set Schedule
     *
     * @param array $options
     * @return $this
     */
    public function setSchedule(array $options)
    {
        $this->schedule = $options;

        return $this;
    }

    /**
     * Set Additional Treatments
     *
     * @param array $options
     * @return $this
     */
    public function setAdditionalTreatments(array $options)
    {
        $this->additional = $options;

        return $this;
    }

    /**
     * Set Description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set Limits
     *
     * @param array $options
     * @return $this
     */
    public function setLimits(array $options)
    {
        $this->limits = $options;

        return $this;
    }

    /**
     * Set Hook
     *
     * @param array $options
     * @return $this
     */
    public function setHook(array $options)
    {
        $this->hook = $options;

        return $this;
    }

    /**
     * Set Segment Information
     *
     * @param string $id
     * @param string $version
     * @return $this
     */
    public function setSegmentInfo(string $id, string $version)
    {
        $this->segmentInfo['id'] = $id;
        $this->segmentInfo['version'] = $version;

        return $this;
    }

    /**
     * Set Treatment
     *
     * @param string $name
     * @param string $description
     * @return $this
     */
    public function setTreatment(string $name, string $description)
    {
        $this->treatment['name'] = $name;
        $this->treatment['description'] = $description;

        return $this;
    }

    /**
     * Create a Campaign in AWS Pinpoint
     *
     * @return mixed
     */
    public function create()
    {
        $this->checkSegmentInfo();

        $campaignRequest = [
            'Name' => $this->name,
            'Schedule' => empty($this->schedule) ? $this->getScheduleDefault() : $this->schedule,
            'IsPaused' => false,
            'MessageConfiguration' => $this->messageConfiguration,
            'SegmentId' => $this->segmentInfo['id'],
            'SegmentVersion' => $this->segmentInfo['version'],
            'TreatmentDescription' => $this->treatment['description'],
            'TreatmentName' => $this->treatment['name'],
            'tags' => $this->tags,
        ];

        return $this->pinpoint->createCampaign([
            'ApplicationId' => $this->appId,
            'WriteCampaignRequest' => $this->setParamsBeforeRequest($campaignRequest)
        ]);
    }

    /**
     * Get Schedule Default
     *
     * @return array
     */
    protected function getScheduleDefault()
    {
        return [
            'EndTime' => '<string>',
            'Frequency' => 'I',
            'StartTime' => Carbon::now(), // REQUIRED
        ];
    }

    /**
     * Set params before send request
     *
     * @param array $params
     * @return array
     */
    protected function setParamsBeforeRequest(array $params)
    {
        if (!empty($this->additional)) {
            array_merge(['AdditionalTreatments' => $this->additional], $params);
        }

        if (!empty($this->description)) {
            array_merge(['Description' => $this->description], $params);
        }

        if (!empty($this->holdoutPercent)) {
            array_merge(['HoldoutPercent' => $this->holdoutPercent], $params);
        }

        if (!empty($this->limits)) {
            array_merge(['Limits' => $this->limits], $params);
        }

        if (!empty($this->hook)) {
            array_merge(['Hook' => $this->hook], $params);
        }

        return $params;
    }

    /**
     * Check Segment
     *
     * @throws /Throwable
     */
    protected function checkSegmentInfo()
    {
        throw_if(
            is_null($this->segmentInfo['id']) ||
            is_null($this->segmentInfo['version']),
            new Exception('Please set information for Segment!')
        );
    }
}