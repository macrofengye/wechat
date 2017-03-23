<?php
namespace WeChat\WeChat\Message;

/**
 * Class Location.
 */
class Location extends AbstractMessage
{
    /**
     * Message type.
     *
     * @var string
     */
    protected $type = 'location';

    /**
     * Properties.
     *
     * @var array
     */
    protected $properties = [
        'latitude',
        'longitude',
        'scale',
        'label',
        'precision',
    ];
}
