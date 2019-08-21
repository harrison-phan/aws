<?php


namespace Si6\AWS\Contracts;


interface PinPointService
{
    /**
     * Get all applications for prop $apps
     *
     * @param array $options | Options: ['PageSize' => '<string>', 'Token' => '<string>',]
     * @return Collection
     */
    public function getApps(array $options = []);

    /**
     * Get a application by Id
     *
     * @param string $id
     * @return mixed
     */
    public function getApp(string $id);

    /**
     * Get Campaigns
     *
     * @param string $appId
     * @param array $options | Options: ['PageSize' => '<string>', 'Token' => '<string>',]
     * @return Collection
     */
    public function getCampaigns(string $appId, array $options = []);

    /**
     * Get Segments
     *
     * @param string $appId
     * @param array $options
     * @return mixed
     */
    public function getSegments(string $appId, array $options = []);

    /**
     * Create new Segment
     *
     * @param string $appId
     * @param string $name
     * @param array $options | Ex: [key => ['<string>',..], ..]
     * @return mixed
     */
    public function createSegment(string $appId, string $name, array $options = []);

    /**
     * Create new Segment
     *
     * @param string $appId
     * @param string $name
     * @param string $segmentId
     * @param array $options
     * @return mixed
     */
    public function createCampaign(string $appId, string $name, string $segmentId, array $options = []);
}