<?php

namespace WeChat\WeChat\OpenPlatform;

use WeChat\WeChat\Server\Guard as ServerGuard;
use WeChat\WeChat\Support\Collection;
use WeChat\WeChat\Support\Log;
use Slim\Http\Response;

class Guard extends ServerGuard
{
    const EVENT_AUTHORIZED = 'authorized';
    const EVENT_UNAUTHORIZED = 'unauthorized';
    const EVENT_UPDATE_AUTHORIZED = 'updateauthorized';
    const EVENT_COMPONENT_VERIFY_TICKET = 'component_verify_ticket';

    /**
     * Event handlers.
     *
     * @var \WeChat\WeChat\Support\Collection
     */
    protected $handlers;

    /**
     * Set handlers.
     *
     * @param array $handlers
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = new Collection($handlers);

        return $this;
    }

    /**
     * Get handlers.
     *
     * @return \WeChat\WeChat\Support\Collection
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * Get handler.
     *
     * @param string $type
     *
     * @return \WeChat\WeChat\OpenPlatform\EventHandlers\EventHandler|null
     */
    public function getHandler($type)
    {
        return $this->handlers->get($type);
    }

    /**
     * {@inheritdoc}
     */
    public function serve()
    {
        $message = $this->getMessage();

        // Handle Messages.
        if (isset($message['MsgType'])) {
            return parent::serve();
        }

        Log::debug('OpenPlatform Request received:', [
            'Method' => $this->request->getMethod(),
            'URI' => $this->request->getUri(),
            'Query' => $this->request->getQueryParams(),
            'Protocal' => $this->request->getServerParam('SERVER_PROTOCOL'),
            'Content' => $this->request->getBody(),
        ]);

        // If sees the `auth_code` query parameter in the url, that is,
        // authorization is successful and it calls back, meanwhile, an
        // `authorized` event, which also includes the auth code, is sent
        // from WeChat, and that event will be handled.
        if ($this->request->getParam('auth_code')) {
            return $this->response->write(self::SUCCESS_EMPTY_RESPONSE);
        }

        $this->handleEventMessage($message);

        return $this->response->write(self::SUCCESS_EMPTY_RESPONSE);
    }

    /**
     * Handle event message.
     *
     * @param array $message
     */
    protected function handleEventMessage(array $message)
    {
        Log::debug('OpenPlatform Event Message detail:', $message);

        $message = new Collection($message);

        $infoType = $message->get('InfoType');

        if ($handler = $this->getHandler($infoType)) {
            $handler->handle($message);
        } else {
            Log::notice("No existing handler for '{$infoType}'.");
        }

        if ($messageHandler = $this->getMessageHandler()) {
            call_user_func_array($messageHandler, [$message]);
        }
    }
}
