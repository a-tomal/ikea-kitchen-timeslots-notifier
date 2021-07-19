<?php

/**
 * Class IkeaKitchen
 *
 * In the ikea store serviceId must be set 1
 * Remotely serviceId must be set 2
 * @property $serviceId;
 *
 * Remotely locationId must be set 21
 * In the ikea store must be set ikea store id
 * @url https://booking.ikea.ru/api/public/new/locations/for/planning/?product=1&type=1
 * @property $locationId;
 */
class IkeaKitchen
{
    const BASE_URL = "https://booking.ikea.ru/api/public";
    const AVAILABLE_DATE = 'date';
    const AVAILABLE_DAYS = 'days';

    protected int $serviceId;
    protected int $locationId;

    public function __construct($serviceId, $locationId)
    {
        $this->serviceId = $serviceId;
        $this->locationId = $locationId;
    }

    /**
     * @return array
     */
    public function getAvailableDays(): array
    {
        return $this->getAvailable(IkeaKitchen::AVAILABLE_DAYS);
    }

    /**
     * @param string $date
     * @return array
     */
    public function getAvailableTimeslotsByDate(string $date): array
    {
        return $this->getAvailable(IkeaKitchen::AVAILABLE_DATE, $date);
    }

    /**
     * @return array
     */
    public function getAvailableTimeslots(): array
    {
        $availableTimeslots = [];
        $days = $this->getAvailableDays();

        foreach ($days as $day) {
            if ($date = $day['date'] ?? null) {
                $timeslots = $this->getAvailableTimeslotsByDate($date);

                foreach ($timeslots as $timeslot) {
                    $availableTimeslots[$timeslot['id']] = [
                        'start' => $timeslot['start'],
                        'end' => $timeslot['end'],
                        'formatted_date' => $timeslot['formatted_date']
                    ];
                }
            }
        }

        return $availableTimeslots;
    }

    /**
     * Date must be a Y-m-d format
     * @param $date
     * Type must be const AVAILABLE_DATE or AVAILABLE_DAYS
     * @param $type
     * @return array
     */
    protected function getAvailable($type, $date = null): array
    {
        $url = $this->getApiEndPoint() . ($type === self::AVAILABLE_DAYS ? ('/' . $type) : '');

        $date = $date ?? date('Y-m-d');
        SimpleCurl::prepare($url, [
            'date' => $date
        ]);
        SimpleCurl::get();

        $responseData = SimpleCurl::getResponseAssoc()['dataArray'] ?? [];

        if ($type === self::AVAILABLE_DAYS) {
            $responseData = $responseData[self::AVAILABLE_DAYS] ?? [];
        }

        return array_filter($responseData, fn($item) => ((bool)$item['available'] ?? false) !== false);
    }

    /**
     * @return string
     */
    protected function getApiEndPoint(): string
    {
        return sprintf('%s/timeslots/service/%d/location/%d', static::BASE_URL, $this->serviceId, $this->locationId);
    }
}