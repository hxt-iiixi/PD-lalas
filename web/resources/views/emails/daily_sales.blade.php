@component('mail::message')
# Daily Sales Report

Here is the sales summary for {{ now()->subDay()->toFormattedDateString() }}:

@foreach($sales as $sale)
- **Time**: {{ $sale->created_at->format('h:i A') }}
- **Product**: {{ $sale->product->name }}
- **Qty**: {{ $sale->quantity }}
- **Discount**: 
    @switch(strtolower($sale->discount_type))
        @case('sc') Senior Citizen (20%) @break
        @case('pwd') PWD (20%) @break
        @default None
    @endswitch
- **Total**: ₱{{ number_format($sale->total_price, 2) }}
---
@endforeach

Thanks,  
Your Pharmacy System
@endcomponent
