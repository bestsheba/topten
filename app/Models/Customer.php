<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getBillingAddressAttribute()
    {
        $address = '';

        if ($this->billing_address_line1) {
            $address .= $this->billing_address_line1;
        }

        if ($this->billing_address_line2) {

            $address .= $this->billing_address_line1 ? ', ' : '';
            $address .= $this->billing_address_line2;
        }

        if ($this->billing_city) {
            $address .= ', ' . $this->billing_address_line2 ? ', ' : '';
            $address .= $this->billing_city;
        }

        if ($this->billing_state) {
            $address .= ', ' . $this->billing_city ? ', ' : '';
            $address .= $this->billing_state;
        }

        if ($this->billing_postal_code) {
            $address .= ', ' . $this->billing_state ? ', ' : '';
            $address .= $this->billing_postal_code;
        }

        if ($this->billing_country) {
            $address .= ', ' . $this->billing_postal_code ? ', ' : '';
            $address .= $this->billing_country;
        }

        return $address;
    }

    public function getShippingAddressAttribute()
    {
        $address = '';

        if ($this->shipping_address_line1) {
            $address .= $this->shipping_address_line1;
        }

        if ($this->shipping_address_line2) {

            $address .= $this->shipping_address_line1 ? ', ' : '';
            $address .= $this->shipping_address_line2;
        }

        if ($this->shipping_city) {
            $address .= ', ' . $this->shipping_address_line2 ? ', ' : '';
            $address .= $this->shipping_city;
        }

        if ($this->shipping_state) {
            $address .= ', ' . $this->shipping_city ? ', ' : '';
            $address .= $this->shipping_state;
        }

        if ($this->shipping_postal_code) {
            $address .= ', ' . $this->shipping_state ? ', ' : '';
            $address .= $this->shipping_postal_code;
        }

        if ($this->shipping_country) {
            $address .= ', ' . $this->shipping_postal_code ? ', ' : '';
            $address .= $this->shipping_country;
        }

        return $address;
    }
}
