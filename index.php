<?php
// helpers.php — CHỈ chứa hàm, không echo HTML dài (trừ renderProductRows chỉ echo <tr>...</tr>)

/**
 * Tính thành tiền của 1 sản phẩm.
 */
function lineTotal(array $product): int
{
    return $product['price'] * $product['qty'];
}

/**
 * Tổng giá trị toàn bộ kho.
 */
function inventoryValue(array $products): int
{
    $sum = 0;
    foreach ($products as $p) {
        $sum += lineTotal($p);
    }
    return $sum;
}

/**
 * Tìm sản phẩm theo SKU. Không tìm thấy -> null.
 */
function findProductBySku(array $products, string $sku): ?array
{
    foreach ($products as $p) {
        if ($p['sku'] === $sku) {
            return $p;
        }
    }
    return null;
}

/**
 * Đếm số sản phẩm thuộc 1 category_id.
 */
function countByCategory(array $products, int $categoryId): int
{
    $count = 0;
    foreach ($products as $p) {
        if ($p['category_id'] === $categoryId) {
            $count++;
        }
    }
    return $count;
}

/**
 * Mức tồn kho theo số lượng.
 * Thứ tự bắt buộc: >= 5 kiểm tra TRƯỚC, rồi mới >= 2.
 */
function stockLevel(array $product): string
{
    $qty = $product['qty'];
    if ($qty >= 5) {
        return 'Du';
    } elseif ($qty >= 2) {
        return 'Sap het';
    } else {
        return 'Can nhap';
    }
}

/**
 * Lọc sản phẩm theo category_id. Nếu $categoryId = null -> trả về nguyên mảng (8 SP).
 */
function filterByCategory(array $products, ?int $categoryId): array
{
    if ($categoryId === null) {
        return $products;
    }

    $result = [];
    foreach ($products as $p) {
        if ($p['category_id'] === $categoryId) {
            $result[] = $p;
        }
    }
    return $result;
}

/**
 * Xếp hạng quy mô kho dựa trên tổng giá trị.
 */
function rankInventory(int $totalValue): string
{
    if ($totalValue < 15_000_000) {
        return 'Nho';
    } elseif ($totalValue < 35_000_000) {
        return 'Trung binh';
    } else {
        return 'Lon';
    }
}

/**
 * Render các dòng <tr> của bảng sản phẩm (bao gồm cột Muc ton).
 * Chỉ echo <tr>...</tr>, không echo <table>/<thead>.
 */
function renderProductRows(array $products, array $categoryMap): void
{
    foreach ($products as $p) {
        $tenDm = $categoryMap[$p['category_id']] ?? '—';
        $thanhTien = lineTotal($p);
        $mucTon = stockLevel($p);

        echo '<tr>';
        echo '<td>' . htmlspecialchars($p['sku'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($tenDm, ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td class="number">' . number_format($p['price'], 0, ',', '.') . '</td>';
        echo '<td class="number">' . $p['qty'] . '</td>';
        echo '<td class="number">' . number_format($thanhTien, 0, ',', '.') . '</td>';
        echo '<td>' . htmlspecialchars($mucTon, ENT_QUOTES, 'UTF-8') . '</td>';
        echo '</tr>';
    }
}