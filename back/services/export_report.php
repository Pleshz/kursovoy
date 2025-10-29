<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/Connection.php';
require_once __DIR__ . '/auth_admin.php';
checkAdminAccess();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()
    ->setCreator("CarShare Admin")
    ->setTitle("Отчёт системы каршеринга")
    ->setDescription("Автоматически сгенерированный отчёт по состоянию на " . date('d.m.Y H:i'));

$carsSheet = $spreadsheet->getActiveSheet();
$carsSheet->setTitle('Автомобили');
$carsSheet->fromArray(['ID', 'Марка', 'Модель', 'Страна', 'Цена (₽/ч)', 'Тип', 'Статус', 'Дата добавления'], NULL, 'A1');

$cars = mysqli_query($db, "SELECT id, brand, model, country, price_per_hour, type, status, created_at FROM cars");
$row = 2;
while ($car = mysqli_fetch_assoc($cars)) {
    $carsSheet->fromArray([
        $car['id'],
        $car['brand'],
        $car['model'],
        $car['country'],
        $car['price_per_hour'],
        $car['type'],
        $car['status'],
        $car['created_at']
    ], NULL, "A$row");
    $row++;
}

$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']]
];
$carsSheet->getStyle('A1:H1')->applyFromArray($headerStyle);
$carsSheet->getColumnDimension('B')->setAutoSize(true);
$carsSheet->getColumnDimension('C')->setAutoSize(true);
$carsSheet->getColumnDimension('D')->setAutoSize(true);

$zonesSheet = $spreadsheet->createSheet();
$zonesSheet->setTitle('Парковки');
$zonesSheet->fromArray(['ID', 'Адрес', 'Мест всего', 'Широта', 'Долгота', 'Дата создания'], NULL, 'A1');

$zones = mysqli_query($db, "SELECT id, address, total_spaces, coordinate_x, coordinate_y, created_at FROM parking_zones");
$row = 2;
while ($zone = mysqli_fetch_assoc($zones)) {
    $zonesSheet->fromArray([
        $zone['id'],
        $zone['address'],
        $zone['total_spaces'],
        $zone['coordinate_x'],
        $zone['coordinate_y'],
        $zone['created_at']
    ], NULL, "A$row");
    $row++;
}
$zonesSheet->getStyle('A1:F1')->applyFromArray($headerStyle);
foreach (range('A', 'F') as $col) {
    $zonesSheet->getColumnDimension($col)->setAutoSize(true);
}

$usersSheet = $spreadsheet->createSheet();
$usersSheet->setTitle('Пользователи');
$usersSheet->fromArray(['ID', 'Логин', 'Роль', 'Дата регистрации'], NULL, 'A1');

$users = mysqli_query($db, "SELECT id, login, role, created_at FROM users");
$row = 2;
while ($user = mysqli_fetch_assoc($users)) {
    $usersSheet->fromArray([
        $user['id'],
        $user['login'],
        $user['role'],
        $user['created_at']
    ], NULL, "A$row");
    $row++;
}
$usersSheet->getStyle('A1:D1')->applyFromArray($headerStyle);
foreach (range('A', 'D') as $col) {
    $usersSheet->getColumnDimension($col)->setAutoSize(true);
}

$ordersSheet = $spreadsheet->createSheet();
$ordersSheet->setTitle('Заказы');
$ordersSheet->fromArray(['ID', 'Клиент', 'Автомобиль', 'Статус', 'Цена (₽)', 'Начало', 'Окончание'], NULL, 'A1');

$orders = mysqli_query($db, "
    SELECT o.id, u.login, c.brand, c.model, o.status, o.total_price, o.start_time, o.end_time
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    LEFT JOIN cars c ON o.car_id = c.id
");
$row = 2;
while ($order = mysqli_fetch_assoc($orders)) {
    $ordersSheet->fromArray([
        $order['id'],
        $order['login'],
        "{$order['brand']} {$order['model']}",
        $order['status'],
        $order['total_price'],
        $order['start_time'],
        $order['end_time']
    ], NULL, "A$row");
    $row++;
}
$ordersSheet->getStyle('A1:G1')->applyFromArray($headerStyle);
foreach (range('A', 'G') as $col) {
    $ordersSheet->getColumnDimension($col)->setAutoSize(true);
}

$filename = 'Отчёт_Каршеринг_' . date('Y-m-d_H-i-s') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
