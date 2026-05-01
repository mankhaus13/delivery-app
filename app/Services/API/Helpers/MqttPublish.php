<?php

declare(strict_types=1);

namespace App\Services\API\Helpers;

use App\Services\Enums\MqttTopic;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;

final readonly class MqttPublish
{
    public function publish(MqttTopic $mqttTopic, int $expeditorId, string $message): void
    {
        try {
            // Create a new instance of an MQTT client and configure it to use the shared broker host and port.
            $client = new MqttClient(
                env('MQTT_HOST'),
                (int) env('MQTT_PORT'),
                'admin',
                MqttClient::MQTT_3_1,
                null,
                null
            );
            // Create and configure the connection settings as required.
            $connectionSettings = (new ConnectionSettings())
                ->setUsername(env('MQTT_AUTH_USERNAME'))
                ->setPassword(env('MQTT_AUTH_PASSWORD'));

            // Connect to the broker without specific connection settings but with a clean session.
            $client->connect($connectionSettings, true);

            $topic = $mqttTopic->value . $expeditorId;
            $client->publish($topic, $message, MqttClient::QOS_EXACTLY_ONCE);

            // Since QoS 2 requires the publisher to await confirmation and resend the message if no confirmation is
            // received,
            // we need to start the client loop which takes care of that. By passing `true` as second parameter,
            // we allow the loop to exit as soon as all confirmations have been received.
            $client->loop(true, true);

            // Gracefully terminate the connection to the broker.
            $client->disconnect();
        } catch (MqttClientException $e) {
            // MqttClientException is the base exception to all exceptions in the library.
            Log::channel('mqtt')->error(
                'Publishing a message failed.',
                ['exception' => $e]
            );
        }
    }
}
