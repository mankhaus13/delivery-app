<?php

declare(strict_types=1);

namespace App\Services\API\Helpers;

use App\Services\Enums\ErpEndpoint;
use App\Traits\Logger;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

use function json_decode;

/*final readonly */ //I cannot stub neither final nor readonly class

class GuzzleERP
{
    use Logger;

    /**
     * @throws Exception
     */
    public function pushChanges(ErpEndpoint $endpoint, array $data): string
    {
        try {
            $httpClient = new Client();
            $response = $httpClient->post(
                env('ERP_URL') . $endpoint->value,
                [
                    'json' => $data,
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'auth' => [
                        env('ERP_USERNAME'),
                        env('ERP_PASSWORD'),
                    ],
                ]
            );

            $responseBody = $response->getBody()->getContents();
            $this->logRequest($responseBody);
            $this->validateResponse($responseBody);

            return $responseBody;
        } catch (GuzzleException $e) {
            $this->logRequest($e->getMessage());
            throw new Exception('Unable to send data to erp');
        }
    }

    /**
     * @throws Exception
     */
    private function validateResponse(string $response): void
    {
        $responseDecoded = json_decode($response, true);
        if ($responseDecoded['error_code'] !== '0' || $responseDecoded['error_message'] !== '') {
            throw new Exception($response);
        }
    }
}
