<?php
require_once "data.php";
require_once "helpers.php";

// Tạo map danh mục
$categoryMap = [];
foreach ($categories as $category) {
    $categoryMap[$category['id']] = $category['name'];
}

// Lọc theo category
$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$listProducts = filterByCategory($products, $categoryId);

// Tổng kho
$total = inventoryValue($products);

// Báo cáo theo danh mục
$report = [];

foreach ($categories as $category) {
    $report[$category['id']] = [
        'name' => $category['name'],
        'count' => 0,
        'value' => 0
    ];
}

foreach ($products as $product) {
    $id = $product['category_id'];
    $report[$id]['count']++;
    $report[$id]['value'] += lineTotal($product);
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>MiniShop - Phiếu 02</title>

    <style>
        body{
            font-family: Arial;
            margin:40px;
        }

        table{
            border-collapse:collapse;
            width:100%;
            margin-bottom:25px;
        }

        table,th,td{
            border:1px solid #000;
        }

        th,td{
            padding:8px;
            text-align:center;
        }

        h2{
            color:#0b5394;
        }

        a{
            text-decoration:none;
            margin-right:10px;
        }
    </style>
</head>

<body>

<h2>Danh sách sản phẩm</h2>

<p>
<a href="index.php">Tất cả</a>
<a href="?category_id=1">Bàn phím</a>
<a href="?category_id=2">Chuột</a>
<a href="?category_id=3">Màn hình</a>
</p>

<table>

<thead>

<tr>
<th>SKU</th>
<th>Tên</th>
<th>Danh mục</th>
<th>Đơn giá</th>
<th>Số lượng</th>
<th>Thành tiền</th>
<th>Mức tồn</th>
</tr>

</thead>

<tbody>

<?php renderProductRows($listProducts,$categoryMap); ?>

</tbody>

</table>

<h2>Báo cáo theo danh mục</h2>

<table>

<thead>

<tr>
<th>Danh mục</th>
<th>Số sản phẩm</th>
<th>Giá trị kho</th>
</tr>

</thead>

<tbody>

<?php foreach($report as $item): ?>

<tr>

<td><?= $item['name'] ?></td>

<td><?= $item['count'] ?></td>

<td><?= number_format($item['value']) ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<h3>Tổng giá trị kho:
<b><?= number_format($total) ?></b>
</h3>

<h3>Quy mô kho:
<b><?= rankInventory($total) ?></b>
</h3>

<!-- MS_EXPECT inventory_value=41380000 rank=Lon -->

</body>
</html>