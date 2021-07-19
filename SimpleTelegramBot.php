<?php

/**
 * @property $id;
 * @property $secretKey;
 */
class SimpleTelegramBot
{
    protected int $id;
    protected string $secretKey;

    /**
     * Telegram bot id
     * @param $botId
     *
     * Telegram secret key
     * @param $secretKey
     */
    public function __construct($botId, $secretKey)
    {
        $this->id = $botId;
        $this->secretKey = $secretKey;
    }

    /**
     * @param int $chatId
     * @param $message
     */
    public function sendMessage(int $chatId, $message): void
    {
        $url = sprintf('%s/%s?%s', $this->getBaseUrl(), __FUNCTION__, http_build_query([
            'chat_id' => $chatId,
            'text' => $message
        ]));

        file_get_contents($url);
    }

    /**
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return sprintf('https://api.telegram.org/bot%d:%s', $this->id, $this->secretKey);
    }
}