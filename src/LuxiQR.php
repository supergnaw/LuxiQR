<?php

declare(strict_types=1);

namespace supergnaw\LuxiQR;

use supergnaw\LuxiQR\constants\ErrorCorrection;
use supergnaw\LuxiQR\factoryMethods\Call;
use supergnaw\LuxiQR\factoryMethods\Email;
use supergnaw\LuxiQR\factoryMethods\Url;
use supergnaw\LuxiQR\factoryMethods\VCard3;
use supergnaw\LuxiQR\factoryMethods\WiFi;
use supergnaw\LuxiQR\factoryMethods\YouTube;
use supergnaw\LuxiQR\traits\EncodeTrait;
use supergnaw\LuxiQR\traits\GaloisFieldTrait;
use supergnaw\LuxiQR\traits\InputValidationTrait;
use supergnaw\LuxiQR\traits\MaskTrait;
use supergnaw\LuxiQR\traits\ModuleMatrixTrait;
use supergnaw\LuxiQR\traits\OutputTrait;
use supergnaw\LuxiQR\traits\ReedSolomonTrait;
use supergnaw\LuxiQR\traits\VersionFormatTrait;

class LuxiQR
{
    // traits
    use EncodeTrait;
    use GaloisFieldTrait;
    use InputValidationTrait;
    use MaskTrait;
    use OutputTrait;
    use ReedSolomonTrait;
    use ModuleMatrixTrait;
    use VersionFormatTrait;

    // factory methods
    use Call;
    use Email;
    use Url;
    use VCard3;
    use WiFi;
    use YouTube;

    // module matrix properties
    protected string $mode = "";
    protected string $eccLevel = ErrorCorrection::MEDIUM;
    protected int $version = 0;
    protected int $characterCount = 0;
    protected int $matrixSize = 0;
    protected array $moduleMatrix = [];
    protected string $data = "";
    protected string $encodedData = "";
    protected array $dataBlocks = [];
    protected array $eccBlocks = [];
    protected array $interleavedBlocks = [];
    protected string $bitstream = "";

    // qr code masking
    protected int $maskVersion = 0;
    protected array $maskMask = [];

    public function __construct(
        string|int $data,
        string     $eccLevel = null,
        string     $mode = null,
        int        $version = null
    )
    {
        $this->initialize(
            data: $data,
            eccLevel: $eccLevel,
            mode: $mode,
            version: $version
        );
    }

    /**
     * Initializes a new Quick Response Code
     *
     * @param string|int $data
     * @param string|null $eccLevel
     * @param string|null $mode
     * @param int|null $version
     * @return void
     */
    public function initialize(
        string|int $data,
        string     $eccLevel = null,
        string     $mode = null,
        int        $version = null,
    ): void
    {
        // input validation
        $this->data = $this->validateData($data);
        $this->eccLevel = $this->validateECCLevel($eccLevel);
        $this->mode = $this->validateMode($mode);
        $this->version = $this->validateVersion($version);

        // generate payload
        $this->encodeData();
        $this->splitDataBlocks();
        $this->generateEccBlocks();
        $this->interleaveBlocks();

        // generate module matrix
        $this->generateMatrix();
    }
}