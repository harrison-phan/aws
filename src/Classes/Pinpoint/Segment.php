<?php

namespace Si6\Aws\Classes\Pinpoint;


/**
 * Refer Document in https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-pinpoint-2016-12-01.html#createsegment
 */
class Segment extends AbstractPinpoint
{
    /** @var array */
    public const DIMENSION_TYPES = [
        'inclusive' => 'INCLUSIVE',
        'exclusive' => 'EXCLUSIVE',
    ];

    /** @var array */
    public const INCLUDE_TYPES = [
        'any'  => 'ANY',
        'all'  => 'ALL',
        'none' => 'NONE'
    ];

    /** @var array */
    protected $attributes = [];

    /** @var array */
    protected $behavior = [];

    /** @var array */
    protected $demographic = [];

    /** @var array */
    protected $location = [];

    /** @var array */
    protected $metrics = [];

    /** @var array */
    protected $userAttributes = [];

    /** @var array */
    protected $segmentGroups = [];

    /**
     * Set Behavior
     *
     * @param array $options
     * @return $this
     */
    public function setBehavior(array $options)
    {
        $this->behavior = $options;

        return $this;
    }

    /**
     * Set Demographic
     *
     * @param array $options
     * @return $this
     */
    public function setDemographic(array $options)
    {
        $this->demographic = $options;

        return $this;
    }

    /**
     * Set Attributes
     *
     * @param array $options
     * @return $this
     */
    public function setAttributes(array $options)
    {
        foreach ($options as $key => $option) {
            $this->attributes[$key] = [
                'AttributeType' => self::DIMENSION_TYPES['inclusive'],
                'Values' => $option
            ];
        }

        return $this;
    }

    /**
     * Set Locations
     *
     * @param array $options
     * @return $this
     */
    public function setLocation(array $options)
    {
        $this->location = $options;
        return $this;
    }

    /**
     * Set Metrics
     *
     * @param array $options
     * @return $this
     */
    public function setMetrics(array $options)
    {
        $this->metrics = $options;

        return $this;
    }

    /**
     * Set User Attributes
     *
     * @param array $options
     * @return $this
     */
    public function setUserAttributes(array $options)
    {
        $this->userAttributes = $options;

        return $this;
    }

    /**
     * Set groups to setSegmentGroups
     *
     * @param array $groups
     * @param string $includeType | Types: ALL|ANY|NONE
     * @return $this
     */
    public function setSegmentGroups(array $groups, string $includeType = null)
    {
        if (!empty($groups)) {
            $this->segmentGroups = [
                'Groups' => $groups,
                'Include' => (is_null($includeType)) ? self::INCLUDE_TYPES['any'] : $includeType
            ];
        }

        return $this;
    }

    /**
     * Create a Segment in AWS Pinpoint
     *
     * @return mixed
     */
    public function create()
    {
        $segmentRequest = [
            'Name' => $this->name,
            'Dimensions' => [
                'Attributes'     => $this->attributes,
                'Behavior'       => $this->behavior,
                'Demographic'    => $this->demographic,
                'Location'       => $this->location,
                'Metrics'        => $this->metrics,
                'UserAttributes' => $this->userAttributes
            ],
            'tags' => $this->tags
        ];

        if ($this->checkSegmentGroups()) {
            array_merge($segmentRequest, ['SegmentGroups' => $this->segmentGroups]);
        }

        return $this->pinpoint->createSegment([
            'ApplicationId' => $this->appId,
            'WriteSegmentRequest' => $segmentRequest,
        ]);
    }

    /**
     * Check Segment Groups
     *
     * @return bool
     */
    protected function checkSegmentGroups()
    {
        return !empty($this->segmentGroups)
            && key_exists('Groups', $this->segmentGroups)
            && !is_null($this->segmentGroups['Groups']);
    }
}