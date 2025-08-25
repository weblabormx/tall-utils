<?php

namespace WeblaborMx\TallUtils\Models;

use Illuminate\Support\Facades\Schema;

trait Searchable
{
    public function scopeSearch($query, $term)
    {
        $columns = $this->getSearchableColumns();
        $words = collect(explode(' ', $term))
            ->map(fn($word) => trim($word))
            ->filter()
            ->unique();

        return $query->where(function ($q) use ($columns, $words) {
            foreach ($words as $word) {
                $q->where(function ($subQuery) use ($columns, $word) {
                    foreach ($columns as $column) {
                        $subQuery->orWhere($column, 'LIKE', "%{$word}%");
                    }
                });
            }
        });
    }

    protected function getSearchableColumns()
    {
        if (property_exists($this, 'searchable') && is_array($this->searchable)) {
            return $this->searchable;
        }

        $table = $this->getTable();
        $columns = Schema::getColumnListing($table);
        $excluded = ['id', 'created_at', 'updated_at', 'deleted_at'];
        return array_values(array_diff($columns, $excluded));
    }
}
