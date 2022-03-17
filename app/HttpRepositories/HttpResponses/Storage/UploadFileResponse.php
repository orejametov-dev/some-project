<?php

namespace App\HttpRepositories\HttpResponses\Storage;

use Alifuz\Utils\Parser\ParseDataTrait;

class UploadFileResponse
{
    use ParseDataTrait;

    public function __construct(
        private int $id,
        private string $uuid,
        private string $key,
        private string $mime_type,
        private int $size,
        private string $storage_path,
        private string $url,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: self::parseInt($data['id']),
            uuid: self::parseString($data['uuid']),
            key: self::parseString($data['key']),
            mime_type: self::parseString($data['mime_type']),
            size: self::parseInt($data['size']),
            storage_path: self::parseString($data['storage_path']),
            url: self::parseString($data['url'])
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mime_type;
    }

    /**
     * @return string
     */
    public function getStoragePath(): string
    {
        return $this->storage_path;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }
}
