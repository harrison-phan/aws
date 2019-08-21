<?php


namespace Si6\Aws;

use Aws\Credentials\CredentialProvider;
use Aws\Sdk;

abstract class BaseAws
{
    /** @var object */
    protected $aws;

    /** @var object */
    protected $instance;

    /**
     * AwsService constructor.
     *
     * @param array $options
     * @return void
     */
    public function __construct(array $options = [])
    {
        $options = !empty($options) ? $options : config('aws');

        // Use the default credential provider
        $options['credentials'] = CredentialProvider::defaultProvider();

        $this->aws = new Sdk($options);
    }

    /**
     * Set value for Instance
     *
     * @param mixed $instance
     */
    public function setInstance($instance): void
    {
        $this->instance = $instance;
    }
}