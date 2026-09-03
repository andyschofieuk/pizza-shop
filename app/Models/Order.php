<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'total_amount'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon(): HasOne
    {
        return $this->hasOne(Coupon::class, 'id', 'coupon_id');
    }

    /**
     * Recalculate total for order
     *
     * @return $this
     */
    public function calculateTotal(): self
    {
        $price = 0;

        $items = $this->items;
        foreach ($items as $item) {
            $qty = $item->quantity;
            $itemType = $item->item_type;

            $itemModel = match($itemType) {
                'drink' => Drink::find($item->item_id),
                'pizza' => Pizza::find($item->item_id)
            };

            $price += ($itemModel->price * $qty);
        }

        if ($this->coupon_id > 0) {
            $discountAmount = $this->coupon->discount_amount;
            $price = round($price - (($discountAmount/100) * $price), 2);
        }

        $this->total_amount = $price;
        $this->save();

        return $this;
    }
}
