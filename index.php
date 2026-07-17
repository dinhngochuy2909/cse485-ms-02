<?php

require_once "data.php";
require_once "helpers.php";

$categoryMap = [];

foreach ($categories as $category) {
    $categoryMap[$category['id']] = $category['name'];
}

$totalInventory = inventoryValue($products);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">

<title>MiniShop - Buoi 2</title>

<style>

body{
    font-family:Arial;
    margin:30px;
}

table{
    border-collapse:collapse;
    width:900px;
}

th,td{
    border:1px solid black;
    padding:8px;
}

th{
    background:#eeeeee;
}

</style>

</head>

<body>

<h2>MiniShop - Buoi 2</h2>

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

<?php foreach($products as $product): ?>

<tr>

<td><?= htmlspecialchars($product['sku']) ?></td>

<td><?= htmlspecialchars($product['name']) ?></td>

<td><?= htmlspecialchars($categoryMap[$product['category_id']]) ?></td>

<td><?= $product['price'] ?></td>

<td><?= $product['qty'] ?></td>

<td><?= lineTotal($product) ?></td>

<td><?= stockLevel($product) ?></td>

</tr>

<?php endforeach; ?>

</table>

<h3>Tong gia tri kho = <?= $totalInventory ?></h3>

<pre>

<?php
var_dump(findProductBySku($products, "MN-02"));
?>

</pre>

</body>

</html>