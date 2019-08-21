<?php

namespace Si6\Aws\Utils;


use Exception;
use Si6\Aws\BaseAws;
use Si6\Aws\Classes\Pinpoint\Campaign;
use Si6\Aws\Classes\Pinpoint\Segment;
use Si6\Aws\Contracts\PinPointService;

class PinPoint extends BaseAws implements PinPointService
{
    /** @var Collection */
    protected $apps = null;

    /**
     * Pinpoint constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        // Create Pinpoint Instance
        $this->setInstance($this->aws->createPinpoint());

        $this->setApps();
    }

    /**
     * Get all applications for prop $apps
     *
     * @param array $options | Options: ['PageSize' => '<string>', 'Token' => '<string>',]
     * @return Collection
     */
    public function getApps(array $options = [])
    {
        $this->setApps($options);

        return $this->apps;
    }

    /**
     * Get a application by Id
     *
     * @param string $id
     * @return mixed
     */
    public function getApp(string $id)
    {
        if (!empty($this->apps)) {
            $this->setApps();
        }

        return $this->apps->filter(function ($app) use ($id) {
            return $app['Id'] == $id;
        });
    }

    /**
     * Get Campaigns
     *
     * @param string $appId
     * @param array $options | Options: ['PageSize' => '<string>', 'Token' => '<string>',]
     * @return Collection
     */
    public function getCampaigns(string $appId, array $options = [])
    {
        $params = ['ApplicationId' => $appId];

        if (!empty($options)) {
            array_merge($params, $options);
        }

        $response = $this->instance->getCampaigns($params);

        return collect($response['CampaignsResponse']['Item']);
    }

    /**
     * Create new Segment
     *
     * @param string $appId
     * @param string $name
     * @param array $options | Ex: [key => ['<string>',..], ..]
     * @return mixed
     */
    public function createSegment(string $appId, string $name, array $options = [])
    {
        $segment = new Segment($this->instance, $appId, $name);

        return $this->setOptionsForInstance($segment, $options)->create();
    }

    /**
     * Create new Segment
     *
     * @param string $appId
     * @param string $name
     * @param string $segmentId
     * @param array $options
     * @return mixed
     */
    public function createCampaign(string $appId, string $name, string $segmentId, array $options = [])
    {
        $campaign = new Campaign($this->instance, $appId, $name);

        if (key_exists('Treatment', $options)) {
            $campaign->setTreatment(
                $options['Treatment']['name'],
                $options['Treatment']['description']
            );
        }

        return $this->setOptionsForInstance($campaign, $options)
                    ->setSegmentInfo($segmentId, $options['SegmentVersion'] ?? 1)
                    ->create();
    }

    /**
     * Get Segments
     *
     * @param string $appId
     * @param array $options
     * @return mixed
     */
    public function getSegments(string $appId, array $options = [])
    {
        return $this->instance->getSegments([
            'ApplicationId' => $appId, // REQUIRED
            $options
        ]);
    }

    /**
     * Set Apps
     *
     * @param array $options | Options: ['PageSize' => '<string>', 'Token' => '<string>',]
     */
    protected function setApps(array $options = [])
    {
        $response = $this->instance->getApps($options);

        $this->apps = collect($response['ApplicationsResponse']['Item']);
    }

    /**
     * Set options for instance
     *
     * @param $instance
     * @param array $options
     * @return mixed
     * @throws /Throwable
     */
    protected function setOptionsForInstance($instance, array $options)
    {
        throw_if(!is_object($instance), new Exception('Instance must be a object.'));

        foreach ($options as $key => $option) {
            $method = 'set' . $key;

            if (method_exists($instance, $method)) {
                $instance->$method($option);
            }
        }

        return $instance;
    }
}