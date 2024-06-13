<?php
/**
 * Created by PhpStorm
 * User: taniamolina
 * Date: 8/2/23
 */
declare(strict_types=1);

namespace Tan\TagLine\Model\Attribute\Backend;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\Exception\LocalizedException;

class TagLine extends AbstractBackend
{
    const MAX_CHARACTERS = 200;

    /**
     * Validate the tag_line attribute value before saving
     *
     * @param Product $object
     * @return bool
     * @throws LocalizedException
     */
    public function validate($object): bool
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $tagLineValue = $object->getData($attributeCode);
        if (mb_strlen($tagLineValue) > self::MAX_CHARACTERS) {
            $error = __('The maximum number of characters allowed for Tag Line is %1.', self::MAX_CHARACTERS);
            throw new LocalizedException($error);
        }
        return true;
    }
}

