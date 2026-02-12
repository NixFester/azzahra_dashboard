<?php
// Seeder: seed_trans_status.php
// Purpose: Populate trans_status column with distinct values across transaksi and order_list tables
// Usage (CLI): php seed_trans_status.php --host=localhost --user=root --pass= --db=azzahra2_azza [--clear]

$options = getopt('', ['host::', 'user::', 'pass::', 'db::', 'clear', 'dry-run']);
$host = $options['host'] ?? '127.0.0.1';
$user = $options['user'] ?? 'root';
$pass = $options['pass'] ?? '';
$db = $options['db'] ?? 'azzahra2_azza';
$shouldClear = array_key_exists('clear', $options);
$dryRun = array_key_exists('dry-run', $options);

$statusValues = ['Konfirmasi', 'Lunas', 'Pelunasan', 'Cencel', 'Baru', 'Return', 'OOW', 'Diproses'];

echo "Seeder will connect to {$user}@{$host} database {$db}\n";
if ($shouldClear) {
    echo "WARNING: Will clear existing trans_status values\n";
}
if ($dryRun) {
    echo "DRY-RUN MODE: No changes will be made\n";
}
echo "\n";

try {
    $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

function seedTableTransStatus(PDO $pdo, string $table, string $idColumn, array $statusValues, bool $shouldClear = false, bool $dryRun = false)
{
    echo "Seeding table: {$table}...\n";
    
    // Get count of rows
    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM `{$table}`");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $rowCount = (int)$result['cnt'];
    
    if ($rowCount === 0) {
        echo "  Table is empty, skipping.\n";
        return;
    }
    
    echo "  Found {$rowCount} rows to process.\n";
    
    // Get all IDs
    $stmt = $pdo->prepare("SELECT `{$idColumn}` FROM `{$table}` ORDER BY `{$idColumn}` ASC");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $statusCount = count($statusValues);
    $updated = 0;
    $statements = [];
    
    foreach ($rows as $index => $row) {
        $id = $row[$idColumn];
        $statusValue = $statusValues[$index % $statusCount];
        
        // Quote ID if it's not numeric
        $idValue = is_numeric($id) ? $id : "'{$id}'";
        $updateStatement = "UPDATE `{$table}` SET `trans_status` = '{$statusValue}' WHERE `{$idColumn}` = {$idValue}";
        $statements[] = $updateStatement;
        
        if ($dryRun && $index < 5) {
            echo "  [DRY-RUN] {$updateStatement}\n";
        }
        
        $updated++;
    }
    
    if ($dryRun) {
        echo "  [DRY-RUN] Would update {$updated} rows with cycling trans_status values\n";
        if ($updated > 5) {
            echo "  [DRY-RUN] ... and " . ($updated - 5) . " more rows\n";
        }
        return;
    }
    
    // Execute all updates
    try {
        // Start transaction for safety
        $pdo->beginTransaction();
        
        foreach ($statements as $stmt) {
            $pdo->exec($stmt);
        }
        
        $pdo->commit();
        echo "  Updated {$updated} rows successfully.\n";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "  ERROR during update: " . $e->getMessage() . "\n";
    }
}

// Seed transaksi table
seedTableTransStatus($pdo, 'transaksi', 'trans_kode', $statusValues, $shouldClear, $dryRun);

// Seed order_list table
seedTableTransStatus($pdo, 'order_list', 'trans_kode', $statusValues, $shouldClear, $dryRun);

echo "\nDone.\n";

?>
