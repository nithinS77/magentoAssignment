<?php

namespace Mcfadyen\custom\Plugins;

class Product
{
    public function aftergetName(\Magento\Catalog\Model\Product $product, $name)
    {
        $price = $product->getData(key: 'price');
        if ($price < 60) {
            $name .= "So cheap";
        } else {
            $name .= "So expensive";
        }
        return $name;
    }
}
