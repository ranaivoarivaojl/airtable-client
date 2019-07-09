<?php

namespace Guym4c\Airtable;

use Guym4c\Airtable\Request\DeleteRequest;
use Guym4c\Airtable\Request\RecordListRequest;
use Guym4c\Airtable\Request\SingleRecordRequest;

class Airtable {

    /** @var string */
    private $key;

    /** @var string */
    private $baseId;

    const API_ENDPOINT = 'https://api.airtable.com/v0';

    public function __construct(string $key, string $baseId) {

        $this->key = $key;
        $this->baseId = $baseId;
    }

    /**
     * Get a single record.
     *
     * @param string $table
     * @param string $id
     * @return Record
     * @throws AirtableApiException
     */
    public function get(string $table, string $id): Record {
        return (new SingleRecordRequest($this, $table, 'GET', $id))
            ->getResponse();
    }

    /**
     * @param string          $table
     * @param ListFilter|null $filter
     * @return RecordListRequest
     * @throws AirtableApiException
     */
    public function list(string $table, ?ListFilter $filter = null): RecordListRequest {

        return (new RecordListRequest($this, $table, empty($filter)
                ? []
                : $filter->jsonSerialize()))
            ->getResponse();
    }

    /**
     * @param string $table
     * @param string $id
     * @param array  $data
     * @param bool   $destructive
     * @return Record
     * @throws AirtableApiException
     */
    public function update(string $table, string $id, array $data, bool $destructive = false): Record {
        return (new SingleRecordRequest($this, $table, $destructive ? 'PUT' : 'PATCH', $id, [], $data))
            ->getResponse();
    }

    /**
     * @param string $table
     * @param array  $data
     * @return Record
     * @throws AirtableApiException
     */
    public function create(string $table, array $data): Record {
        return (new SingleRecordRequest($this, $table, 'POST', '', [], $data))
            ->getResponse();
    }

    /**
     * @param string $table
     * @param string $id
     * @return bool
     * @throws AirtableApiException
     */
    public function delete(string $table, string $id): bool {
        return (new DeleteRequest($this, $table, $id))
            ->getResponse();
    }

    /**
     * @return string
     */
    public function getKey(): string {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getBaseId(): string {
        return $this->baseId;
    }
}