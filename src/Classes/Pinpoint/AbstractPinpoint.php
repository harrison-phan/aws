<?php

namespace Si6\Aws\Classes\Pinpoint;


use Aws\Pinpoint\PinpointClient;

abstract class AbstractPinpoint
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $appId;

    /** @var PinpointClient */
    protected $pinpoint;

    /** @var array */
    protected $tags = [];

    /**
     * Constructor.
     *
     * @param PinpointClient $pinPoint
     * @param string $appId
     * @param string $name
     */
    public function __construct(PinpointClient $pinPoint, string $appId, string $name)
    {
        $this->appId = $appId;
        $this->name = $name;
        $this->pinpoint = $pinPoint;
    }

    /**
     * Set tags
     *
     * @param array $tags
     * @return $this
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Create Instance in AWS Pinpoint
     *
     * @return mixed
     */
    abstract public function create();
}