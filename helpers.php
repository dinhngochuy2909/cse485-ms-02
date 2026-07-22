<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/helpers.php';

// Map category_id => tên danh mục (dùng lại như Phiếu 01)
$categoryMap = [];
foreach ($categories as $c) {
    $categoryMap[$c['id']] = $c['name'];
}

// ---- A. Filter theo $_GET['category_id'] ----
// Không có category_id -> null -> filterByCategory trả về nguyên 8 SP
$categoryIdParam = $_GET['category_id'] ?? null;
$selectedCategoryId = ($categoryIdParam === null || $categoryIdParam === '')
    ? null
    : (int) $categoryIdParam;

$filteredProducts = filterByCategory($products, $selectedCategoryId);

// ---- B. Tổng giá trị kho + rank (LUÔN tính trên toàn bộ 8 SP, không phụ thuộc filter) ----
$totalValue = inventoryValue($products);
$rank = rankInventory($totalValue);

// ---- C. Báo cáo theo 3 danh mục ----
$report = [];
foreach ($categories as $c) {
    $catId = $c['id'];
    $itemsInCat = filterByCategory($products, $catId);
    $report[] = [
        'name'  => $c['name'],
        'count' => countByCategory($products, $catId),
        'value' => inventoryValue($itemsInCat),
    ];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>MiniShop — Bao cao kho (Buoi 2)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        table { border-collapse: collapse; width: 100%; max-width: 950px; margin-bottom: 24px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        th { background: #f2f2f2; }
        td.number { text-align: right; }
        tfoot td { font-weight: bold; background: #fafafa; }
        .filters a { margin-right: 12px; }
        .filters a.active { font-weight: bold; text-decoration: underline; }
        .summary { margin-top: 8px; }
    </style>
</head>
<body>
    <h1>MiniShop — Bao cao kho (Buoi 2)</h1>

    <p class="filters">
        Loc theo danh muc:
        <a href="index.php" class="<?php echo $selectedCategoryId === null ? 'active' : ''; ?>">Tat ca</a>
        <?php foreach ($categories as $c): ?>
            <a href="index.php?category_id=<?php echo (int) $c['id']; ?>"
               class="<?php echo $selectedCategoryId === $c['id'] ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8'); ?>
            </a>
        <?php endforeach; ?>
    </p>

    <h2>Danh sach san pham (<?php echo count($filteredProducts); ?> dong)</h2>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Ten</th>
                <th>Danh muc</th>
                <th>Gia</th>
                <th>So luong</th>
                <th>Thanh tien</th>
                <th>Muc ton</th>
            </tr>
        </thead>
        <tbody>
            <?php renderProductRows($filteredProducts, $categoryMap); ?>
        </tbody>
    </table>

    <h2>Bao cao theo danh muc</h2>
    <table>
        <thead>
            <tr>
                <th>Danh muc</th>
                <th>So SP</th>
                <th>Tong gia tri</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report as $r): ?>
            <tr>
                <td><?php echo htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="number"><?php echo $r['count']; ?></td>
                <td class="number"><?php echo number_format($r['value'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Tong cong</td>
                <td class="number"><?php echo count($products); ?></td>
                <td class="number"><?php echo number_format($totalValue, 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <p>Tong gia tri kho (inventoryValue) = <?php echo $totalValue; ?></p>
        <p>Quy mo kho (rankInventory) = <?php echo htmlspecialchars($rank, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <!-- MS_EXPECT inventory_value=<?php echo $totalValue; ?> rank=<?php echo $rank; ?> -->
</body>
</html>