<?php
/**
 * Created by PhpStorm
 * User: tan
 * Project: Demo
 * Date: 05/06/2024
 */

declare(strict_types=1);

namespace Tan\BadWeather\Setup\Patch\Data;

use Magento\SalesRule\Model\RuleFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\App\State;
use Magento\SalesRule\Model\Coupon;

class CreateRule implements DataPatchInterface
{
    const BAD_WEATHER_COUPON_CODE = 'BADWEATHER';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var RuleFactory
     */
    private $ruleFactory;
    private Logger $logger;
    private State $state;
    private Coupon $coupon;

    /**
     * CreateRule constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param RuleFactory $ruleFactory
     * @param Logger $logger
     * @param State $state
     * @param Coupon $coupon
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        RuleFactory              $ruleFactory,
        Logger                   $logger,
        State                    $state,
        Coupon                   $coupon
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->ruleFactory = $ruleFactory;
        $this->logger = $logger;
        $this->state = $state;
        $this->coupon = $coupon;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(): void
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $coupon = $this->coupon->load(self::BAD_WEATHER_COUPON_CODE, 'code');
        if ($coupon == null ) {
            $rule = $this->ruleFactory->create();
            $rule->setName('Bad Weather Discount')
                ->setDescription('20% Discount for Bad Weather')
                ->setCouponType(\Magento\SalesRule\Model\Rule::COUPON_TYPE_SPECIFIC)
                ->setCouponCode('BADWEATHER')
                ->setUsesPerCustomer(1)
                ->setIsActive(1)
                ->setConditionsSerialized('')
                ->setActionsSerialized('')
                ->setStopRulesProcessing(0)
                ->setIsAdvanced(0)
                ->setProductIds('')
                ->setSortOrder(0)
                ->setSimpleAction(\Magento\SalesRule\Model\Rule::BY_PERCENT_ACTION)
                ->setDiscountAmount(20)
                ->setDiscountStep(0)
                ->setApplyToShipping(0)
                ->setIsRss(0)
                ->setWebsiteIds('1'); // Adjust website ID if needed
            try {
                $rule->save();
            } catch (\Exception $e) {
                $this->logger->error('[Tan_BadWeather] CreateRule Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }
}
