<?php
namespace Dotmailer\Entity;

final class ContactImportStatus implements Arrayable
{
    /**
     * The import has been accepted and has successfully imported
     */
    const STATUS_FINSIHED = 'Finished';
    
    /**
     * The import has been accepted, but has not yet finished
     */
    const STATUS_NOT_FINISHED = 'NotFinished';
    
    /**
     * The import was rejected by the Data Watchdog due to concerns with the quality of your email data; please
     * contact support
     */
    const STATUS_REJECTED_BY_WATCHDOG = 'RejectedByWatchdog';
    
    /**
     * The file imported was not in the advised CSV or Excel format
     */
    const STATUS_INVALID_FILE_FORMAT = 'InvalidFileFormat';
    
    /**
     * The import has an unknown state which could indicate an invalid import ID; please contact support
     */
    const STATUS_UNKNOWN = 'Unknown';
    
    /**
     * The import has failed; please contact support
     */
    const STATUS_FAILED = 'Failed';
    
    /**
     * The contacts you're trying to import exceeds your account's contact limit
     */
    const STATUS_EXCEEDS_ALLOWED_CONTACT_LIMIT = 'ExceedsAllowedContactLimit';
    
    /**
     * This feature is not available in the version of the API you're using
     */
    const STATUS_NOT_AVAILABLE_IN_THIS_VERSION = 'NotAvailableInThisVersion';
    
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $status;

    /**
     * @param int|null $id
     * @param string $status
     */
    public function __construct(
        string $id,
        string $status
    ) {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
        ];
    }
}
