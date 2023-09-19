<?php

namespace Codedor\FilamentRedirects\Imports;

use Codedor\FilamentRedirects\Models\Redirect;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class RedirectsImport implements ToCollection, WithHeadingRow, WithBatchInserts
{
    private int $defaultStatus;

    public function __construct()
    {
        $this->defaultStatus = config('filament-redirects.default-status');
    }

    public function collection(Collection $rows)
    {
        $created = null;
        $updated = null;
        $offlineCount = null;

        $rows->each(function ($row) use (&$created, &$updated, &$offlineCount) {
            if ($row->has('from') && ($row['from'] !== null)) {
                $from = $this->removeTrailingSlashes($row['from']);
                $to = $this->removeTrailingSlashes($row['to']);

                if ($from && $from !== $to) {
                    $urlMap = Redirect::updateOrCreate(
                        [
                            'from' => $from,
                        ],
                        [
                            'to' => $to,
                            'status' => $row['status'] ?? $this->defaultStatus,
                            'online' => 1,
                        ]
                    );
                    $urlMap->wasRecentlyCreated ? $created++ : $created;
                    $urlMap->wasChanged() ? $updated++ : $updated;
                }
            }
        });
    }

    public function removeTrailingSlashes($value)
    {
        if (! $value) {
            return $value;
        }

        if (Str::endsWith($value, '/')) {
            return Str::replaceLast('/', '', $value);
        }

        return $value;
    }

    public function batchSize(): int
    {
        return 100;
    }
}