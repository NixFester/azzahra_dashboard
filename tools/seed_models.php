<?php
// Seeder: seed_models.php
// Usage (CLI): php seed_models.php --host=localhost --user=root --pass=secret --db=azzahra2_azza [--dry-run]

$options = getopt('', ['host::', 'user::', 'pass::', 'db::', 'dry-run']);
$host = $options['host'] ?? '127.0.0.1';
$user = $options['user'] ?? 'root';
$pass = $options['pass'] ?? '';
$db = $options['db'] ?? 'azzahra2_azza';
$dryRun = array_key_exists('dry-run', $options);

echo "Seeder will connect to {$user}@{$host} database {$db}\n";
try {
    $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

function getTables(PDO $pdo, string $dbName): array
{
    $stmt = $pdo->prepare('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = :db');
    $stmt->execute([':db' => $dbName]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getColumns(PDO $pdo, string $dbName, string $table): array
{
    $stmt = $pdo->prepare('SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, EXTRA, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :table ORDER BY ORDINAL_POSITION');
    $stmt->execute([':db' => $dbName, ':table' => $table]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function tableHasRows(PDO $pdo, string $table): bool
{
    $stmt = $pdo->prepare("SELECT 1 FROM `{$table}` LIMIT 1");
    try {
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    } catch (Exception $e) {
        // table might not be accessible; treat as no rows
        return false;
    }
}

// Define distinct values for specific columns
$columnValueMaps = [
    'trans_status' => ['Konfirmasi', 'Lunas', 'Pelunasan', 'Cencel', 'Baru', 'Return', 'OOW', 'Diproses'],
];

// Track current index for cycling through values
$columnValueIndex = [];

function sampleValue(array $col, $rowIndex = 0)
{
    global $columnValueMaps, $columnValueIndex;
    
    $name = $col['COLUMN_NAME'];
    $type = strtolower($col['DATA_TYPE']);
    $colType = $col['COLUMN_TYPE'];
    $nullable = ($col['IS_NULLABLE'] === 'YES');
    $extra = $col['EXTRA'];

    if (stripos($extra, 'auto_increment') !== false) {
        return null; // skip auto-increment columns
    }

    // Check if this column has predefined values
    if (isset($columnValueMaps[$name])) {
        if (!isset($columnValueIndex[$name])) {
            $columnValueIndex[$name] = 0;
        }
        $values = $columnValueMaps[$name];
        $value = $values[$columnValueIndex[$name] % count($values)];
        $columnValueIndex[$name]++;
        return $value;
    }

    if (strpos($colType, 'enum(') === 0) {
        // pick first enum option
        if (preg_match("/enum\\((.*)\\)/i", $colType, $m)) {
            $opts = str_getcsv($m[1], ',', "'");
            return $opts[0] ?? '""';
        }
    }

    switch ($type) {
        case 'int':
        case 'tinyint':
        case 'smallint':
        case 'mediumint':
        case 'bigint':
        case 'decimal':
        case 'float':
        case 'double':
            return 1;
        case 'date':
            return date('Y-m-d');
        case 'datetime':
        case 'timestamp':
            return date('Y-m-d H:i:s');
        case 'time':
            return date('H:i:s');
        case 'char':
        case 'varchar':
        case 'text':
        case 'mediumtext':
        case 'longtext':
        default:
            return 'seed_' . substr(md5($name . microtime(true)), 0, 8);
    }
}

$tables = getTables($pdo, $db);
echo "Found " . count($tables) . " tables in schema.\n";

// Disable foreign checks to make inserts easier
if (! $dryRun) {
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
}

foreach ($tables as $table) {
    echo "Processing table: {$table}... ";
    if (tableHasRows($pdo, $table)) {
        echo "already has rows, skipping.\n";
        continue;
    }

    $cols = getColumns($pdo, $db, $table);
    if (empty($cols)) {
        echo "no columns found, skipping.\n";
        continue;
    }

    $insertCols = [];
    $insertVals = [];
    $rowIndex = 0;
    foreach ($cols as $col) {
        // skip auto-increment
        if (stripos($col['EXTRA'], 'auto_increment') !== false) {
            continue;
        }
        $value = sampleValue($col, $rowIndex);
        if ($value === null) {
            continue;
        }
        $insertCols[] = "`{$col['COLUMN_NAME']}`";
        // numeric types are not quoted
        $type = strtolower($col['DATA_TYPE']);
        if (in_array($type, ['int','tinyint','smallint','mediumint','bigint','decimal','float','double'])) {
            $insertVals[] = $value;
        } else {
            // escape single quotes
            $safe = str_replace("'", "\\'", (string)$value);
            $insertVals[] = "'{$safe}'";
        }
    }

    if (empty($insertCols)) {
        echo "no insertable columns, skipping.\n";
        continue;
    }

    $sql = 'INSERT INTO `' . $table . '` (' . implode(',', $insertCols) . ') VALUES (' . implode(',', $insertVals) . ')';
    echo "will insert." . ($dryRun ? " (dry-run)" : "") . "\n";
    if ($dryRun) {
        echo $sql . "\n\n";
        continue;
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        echo "inserted id: " . $pdo->lastInsertId() . "\n";
    } catch (Exception $e) {
        echo "failed to insert: " . $e->getMessage() . "\n";
    }
}

if (! $dryRun) {
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
}

echo "Done.\n";

?>
