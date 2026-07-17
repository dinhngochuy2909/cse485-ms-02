<?php

function lineTotal(array $product): int
{
    return $product['price'] * $product['qty'];
}

function inventoryValue(array $products): int
{
    $total = 0;

    foreach ($products as $product) {
        $total += lineTotal($product);
    }

    return $total;
}

function findProductBySku(array $products, string $sku): ?array
{
    foreach ($products as $product) {
        if ($product['sku'] === $sku) {
            return $product;
        }
    }

    return null;
}

function countByCategory(array $products, int $categoryId): int
{
    $count = 0;

    foreach ($products as $product) {
        if ($product['category_id'] === $categoryId) {
            $count++;
        }
    }

    return $count;
}

function stockLevel(array $product): string
{
    if ($product['qty'] >= 5) {
        return "Du";
    } elseif ($product['qty'] >= 2) {
        return "Sap het";
    }

    return "Can nhap";
}