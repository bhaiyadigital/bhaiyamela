<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SyncTable extends Command
{
    protected $signature = 'db:merge {file} {table?}';
    protected $description = 'Merge SQL dump into existing table with new IDs';

    public function handle()
    {
        $file  = $this->argument('file');
        $table = $this->argument('table');

        if (!File::exists($file)) {
            $this->error("SQL file not found: $file");
            return 1;
        }

        $sql = File::get($file);

        // 1. Strip block comments  /* ... */
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        // 2. Strip line comments  -- ...
        $sql = preg_replace('/--[^\n]*/', '', $sql);

        // 3. Strip # comments  # ...
        $sql = preg_replace('/#[^\n]*/', '', $sql);

        // 4. Detect table name from the dump if not provided
        if (!$table) {
            if (preg_match('/INSERT INTO\s+`?(\w+)`?\s*\(/i', $sql, $m)) {
                $table = $m[1];
                $this->info("Auto-detected table: {$table}");
            } else {
                $this->error("Could not detect table name. Pass it as second argument.");
                return 1;
            }
        }

        // 5. Extract all INSERT statements for this table
        preg_match_all(
            "/INSERT INTO\s+`?{$table}`?\s*\((.*?)\)\s*VALUES\s*((?:\((?:[^)(]|\((?:[^)(]|\([^)(]*\))*\))*\)\s*,?\s*)+)/is",
            $sql,
            $allMatches,
            PREG_SET_ORDER
        );

        if (empty($allMatches)) {
            $this->warn("No INSERT statements found for table `{$table}`.");
            return 0;
        }

        $inserted = 0;
        $errors   = 0;

        foreach ($allMatches as $match) {

            // Parse columns, strip backticks and spaces
            $columns = array_map(
                fn($c) => trim(str_replace('`', '', $c)),
                explode(',', $match[1])
            );

            // Find and remove the 'id' column
            $idIndex = array_search('id', $columns);
            if ($idIndex !== false) {
                unset($columns[$idIndex]);
                $columns = array_values($columns);
            }

            $columnList = '`' . implode('`, `', $columns) . '`';

            // Extract each (value, value, ...) group
            preg_match_all(
                '/\((?:[^)(\'"]|\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*"|\((?:[^)(\'"]|\'(?:\\\\.|[^\'])*\'|"(?:\\\\.|[^"])*")*\))*\)/s',
                $match[2],
                $valueGroups
            );

            foreach ($valueGroups[0] as $valueGroup) {

                $inner  = substr($valueGroup, 1, -1); 
                $values = $this->splitValues($inner);

                if ($idIndex !== false && isset($values[$idIndex])) {
                    unset($values[$idIndex]);
                    $values = array_values($values);
                }

                if (count($values) !== count($columns)) {
                    $this->warn("Column/value count mismatch, skipping row.");
                    $errors++;
                    continue;
                }

                $valueSql = '(' . implode(', ', $values) . ')';

                try {
                    $typeIndex = array_search('type', $columns);

                    if ($typeIndex !== false) {
                        $typeValue = trim($values[$typeIndex], "'\"");
                        $exists = DB::table($table)->where('type', $typeValue)->exists();
                        if ($exists) {
                            $this->line("Skipped (type '{$typeValue}' already exists).");
                            continue;
                        }
                    }

                    DB::unprepared("INSERT INTO `{$table}` ({$columnList}) VALUES {$valueSql};");
                    $inserted++;
                } catch (\Exception $e) {
                    $this->warn("Row skipped: " . $e->getMessage());
                    $errors++;
                }
            }
        }

        $this->info("Done — {$inserted} row(s) inserted, {$errors} skipped.");
        return 0;
    }

    /**
     * Split a CSV value string while respecting quoted strings and nested parens.
     */
    private function splitValues(string $input): array
    {
        $values  = [];
        $current = '';
        $inQuote = false;
        $quote   = '';
        $depth   = 0;
        $len     = strlen($input);

        for ($i = 0; $i < $len; $i++) {
            $char = $input[$i];

            if ($inQuote) {
                $current .= $char;
                if ($char === '\\' && $i + 1 < $len) {
                    $current .= $input[++$i]; // escaped character
                    continue;
                }
                if ($char === $quote) {
                    $inQuote = false;
                }
            } else {
                if ($char === "'" || $char === '"') {
                    $inQuote = true;
                    $quote   = $char;
                    $current .= $char;
                } elseif ($char === '(') {
                    $depth++;
                    $current .= $char;
                } elseif ($char === ')') {
                    $depth--;
                    $current .= $char;
                } elseif ($char === ',' && $depth === 0) {
                    $values[] = trim($current);
                    $current  = '';
                } else {
                    $current .= $char;
                }
            }
        }

        if (trim($current) !== '') {
            $values[] = trim($current);
        }

        return $values;
    }
}
