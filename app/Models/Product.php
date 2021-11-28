<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Product extends Model implements AuditableContract
{
    use Auditable;
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'category_id',
        'sku',
        'price',
        'quantity',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'category_id' => 'integer',
        'price' => 'double',
        'quantity' => 'integer',
    ];

    /**
     * @var mixed[]
     */
    protected $auditInclude = [
        'name',
        'category_id',
        'sku',
        'price',
        'quantity',
    ];

    /**
     * @var mixed[]
     */
    protected $auditEvents = [
        'updated',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
