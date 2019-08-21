<?php


namespace Si6\AWS\Contracts;


interface SnsService
{
    /**
     * Push a Message to SNS
     *
     * @param string $topicArn
     * @param string $message
     * @return mixed
     */
    public function publish(string $topicArn, string $message);
}