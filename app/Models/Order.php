<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'delivery_address',
        'phone',
        'note',
    ];

    protected $casts = [
        'total_price' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function canBeCancelled()
    {
        return $this->status === 'pending';
    }

    public function cancel() 
    {
        if (!$this->canBeCancelled()) {
            return false;
        }
        return DB::transaction(function () {
            foreach ($this->orderItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->increment('quantity', $item->quantity);
                }
            }

            $this->update(['status' => 'canceled']);
            return true;
        });
    }

    public function getTotalItems()
    {
        return $this->orderItems->sum('quantity');
    }
}
