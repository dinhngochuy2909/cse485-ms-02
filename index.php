<?php

require_once "data.php";
require_once "helpers.php";

$categoryMap = [];

foreach ($categories as $category) {
    $categoryMap[$category['id']] = $category['name'];
}

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

$filteredProducts = filterByCategory($products, $categoryId);

$totalInventory = inventoryValue($products);

$report = [];

foreach ($categories as $category) {

    $count = countByCategory($products, $category['id']);

    $value = 0;

    foreach ($products as $product) {

        if ($product['category_id'] === $category['id']) {
            $value += lineTotal($product);
        }
    }

    $report[] = [
        'name' => $category['name'],
        'count' => $count,
        'value' => $value
    ];
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">

<title>MiniShop - Homework</title>

<style>

body{
    font-family: Arial;
    margin:30px;
}

table{
    border-collapse:collapse;
    width:100%;
    margin-bottom:20px;
}

th,td{
    border:1px solid #000;
    padding:8px;
    text-align:left;
}

th{
    background:#f2f2f2;
}

a{
    margin-right:10px;
    text-decoration:none;
}

</style>

</head>

<body>

<h2>MiniShop - Homework</h2>

<p>

<a href="index.php">Tat ca</a>

<a href="?category_id=1">Ban phim</a>

<a href="?category_id=2">Chuot</a>

<a href="?category_id=3">Man hinh</a>

</p>

<table>

<tr>

<th>SKU</th>
<th>Ten</th>
<th>Danh muc</th>
<th>Gia</th>
<th>So luong</th>
<th>Thanh tien</th>
<th>Muc ton</th>

</tr>

<?php renderProductRows($filteredProducts, $categoryMap); ?>

</table>

<h3>Tong gia tri kho = <?= $totalInventory ?></h3>

<h3>Quy mo kho: <?= rankInventory($totalInventory) ?></h3>

<h2>Bao cao theo danh muc</h2>

<table>

<tr>

<th>Danh muc</th>
<th>So SP</th>
<th>Tong gia tri</th>

</tr>

<?php foreach($report as $item): ?>

<tr>

<td><?= htmlspecialchars($item['name']) ?></td>

<td><?= $item['count'] ?></td>

<td><?= $item['value'] ?></td>

</tr>

<?php endforeach; ?>

</table>

<pre>

<?php
var_dump(findProductBySku($products, "MN-02"));
?>

</pre>

<!-- MS_EXPECT inventory_value=41380000 rank=Lon -->

</body>

</html>