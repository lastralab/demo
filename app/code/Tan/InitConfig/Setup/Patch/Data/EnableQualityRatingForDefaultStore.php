<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 02/13/2024
 */

declare(strict_types=1);

namespace Tan\InitConfig\Setup\Patch\Data;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Psr\Log\LoggerInterface as Logger;

class EnableQualityRatingForDefaultStore implements DataPatchInterface, PatchRevertableInterface
{
    private const DEFAULT_STORE_ID = 1;
    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resourceConnection;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @param ResourceConnection $resourceConnection
     * @param Logger $logger
     */
    public function __construct(ResourceConnection $resourceConnection, Logger $logger)
    {
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
    }

    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [BaseConfiguration::class];
    }

    /**
     * @return void
     */
    public function apply(): void
    {
        try {
            $connection = $this->resourceConnection->getConnection();
            $tableRating = $connection->getTableName('rating');
            $select = "SELECT rating_id FROM " . $tableRating . " WHERE rating_code like 'Quality'";
            $rating_id = (int) $connection->fetchOne($select);
            $connection = $this->resourceConnection->getConnection();
            $tableRatingStore = $connection->getTableName('rating_store');
            $tableColumns = ['rating_id', 'store_id'];
            $tableValues = [$rating_id, self::DEFAULT_STORE_ID];
            $connection->insertArray($tableRatingStore, $tableColumns, [$tableValues]);
        } catch (\Exception $e) {
            $this->logger->error('[Tan_InitConfig] Error: ' . $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function revert(): void
    {
        try {
            $connection = $this->resourceConnection->getConnection();
            $table = $connection->getTableName('rating_store');
            $parentTable = 'rating';
            $id = self::DEFAULT_STORE_ID;
            $query = "DELETE FROM `" . $table . "` WHERE store_id = $id  AND rating_id = (SELECT rating_id FROM " . $parentTable . " WHERE rating_code like 'Quality')";
            $connection->query($query);
        } catch  (\Exception $e) {
            $this->logger->error('[Tan_InitConfig] Error: ' . $e->getMessage());
        }
    }
}
