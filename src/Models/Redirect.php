<?php

namespace Codedor\FilamentRedirects\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Redirect extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    protected $fillable = [
        'sort_order',
        'from',
        'to',
        'status',
        'pass_query_string',
        'online',
    ];

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('sort_order');
        });
    }

    public function getCleanFromAttribute()
    {
        return (
            Str::startsWith($this->from, '/') || Str::startsWith($this->from, 'http')
                ? ''
                : '/'
        ) . $this->from;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new UrlMapFactory();
    }
}
