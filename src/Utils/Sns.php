<?php

namespace Si6\Aws\Utils;


use Si6\Aws\BaseAws;
use Si6\Aws\Contracts\SnsService;

class Sns extends BaseAws implements SnsService
{
    /**
     * SNS constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        // Create SNS Instance
        $this->setInstance($this->aws->createSns());
    }

    /**
     * Push a Message to SNS
     *
     * @param string $topicArn
     * @param string $message
     * @return mixed
     */
    public function publish(string $topicArn, string $message)
    {
        $payload = [
            'TopicArn' => $topicArn,
            'Message' => $message,
            'MessageStructure' => 'string',
        ];

        return $this->instance->publish($payload);
    }
}