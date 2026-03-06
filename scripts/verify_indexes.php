<?php
// Temporary script to verify enterprise indexes — safe to delete after use

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = [
    'warehouses', 'stores', 'categories', 'units', 'tax_rates', 'item_types',
    'denomination_types', 'income_expense_types', 'partnership_types',
    'transport_types', 'payment_types', 'machine_data', 'products',
    'vendor_details', 'admins', 'users',
];

echo "\n========================================\n";
echo "  ENTERPRISE INDEX VERIFICATION REPORT\n";
echo "========================================\n";

$totalIndexes = 0;
$partialCount = 0;

foreach ($tables as $t) {
    $indexes = DB::select(
        "SELECT indexname, indexdef FROM pg_indexes WHERE tablename = ? AND schemaname = 'public' ORDER BY indexname",
        [$t]
    );
    $count = count($indexes);
    $totalIndexes += $count;
    echo "\n--- {$t} ({$count} indexes) ---\n";
    foreach ($indexes as $i) {
        $isPartial = str_contains($i->indexdef, 'WHERE') ? ' [PARTIAL]' : '';
        if ($isPartial) $partialCount++;
        echo "  {$i->indexname}{$isPartial}\n";
    }
}

echo "\n========================================\n";
echo "  TOTAL: {$totalIndexes} indexes across " . count($tables) . " tables\n";
echo "  PARTIAL INDEXES: {$partialCount}\n";
echo "========================================\n";

// Run EXPLAIN ANALYZE on key DataTable query patterns
echo "\n========================================\n";
echo "  EXPLAIN ANALYZE — Key Query Patterns\n";
echo "========================================\n";

$queries = [
    'Pattern A: Default pagination (units)' =>
        "SELECT * FROM units WHERE deleted_at IS NULL ORDER BY id DESC LIMIT 10",
    'Pattern B: Status filter (categories)' =>
        "SELECT * FROM categories WHERE deleted_at IS NULL AND status = 1 ORDER BY id DESC LIMIT 10",
    'Pattern C: Date range (stores)' =>
        "SELECT * FROM stores WHERE deleted_at IS NULL AND created_at >= '2025-01-01' AND created_at <= '2026-12-31' ORDER BY id DESC LIMIT 10",
    'Pattern D: Vendor global scope' =>
        "SELECT * FROM users WHERE deleted_at IS NULL AND user_type IN (1, 3) AND status = 1 ORDER BY id DESC LIMIT 10",
    'Pattern E: Partner global scope' =>
        "SELECT * FROM admins WHERE deleted_at IS NULL AND user_type IN ('1','2','3') AND status = 1 ORDER BY id DESC LIMIT 10",
    'Pattern F: FK lookup (products.item_type_id)' =>
        "SELECT * FROM products WHERE deleted_at IS NULL AND item_type_id = 1 LIMIT 10",
];

foreach ($queries as $label => $sql) {
    echo "\n--- {$label} ---\n";
    echo "SQL: {$sql}\n";
    $plan = DB::select("EXPLAIN ANALYZE {$sql}");
    foreach ($plan as $row) {
        $line = (array) $row;
        echo "  " . reset($line) . "\n";
    }
}
