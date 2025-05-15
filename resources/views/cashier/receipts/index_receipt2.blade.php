<div id="invoice-POS" style="box-shadow: 0 0 10px rgba(0,0,0,0.2); border-radius: 5px;">
    <!-- Header -->
    <div class="receipt-header text-center mb-2" style="padding: 10px 0;">
        <img src="{{ asset('assets/images/kc.png') }}" alt="Logo" class="receipt-logo" style="max-width: 50px; height: auto; padding-bottom: 10px;">
        <h2 class="receipt-title" style="font-size: 14px; font-weight: bold; margin: 5px 0 0 0;">KC PRIME ENTERPRISE</h2>
        <p class="receipt-address" style="font-size: 10px; margin: 2px 0;">Dr. V Locsin Taclobo, Dumaguete City</p>
        <p class="receipt-contact" style="font-size: 10px; margin: 2px 0 5px 0;">Phone: 0912-345-6789 | Email: capstone17@gmail.com</p>
        <div class="receipt-divider" style="border-top: 1px dashed #ccc; margin: 5px 0;"></div>
    </div>

    <!-- Customer Info -->
    <div class="receipt-customer mb-2" style="padding: 0 5px;">
        <div class="d-flex justify-content-between" style="margin: 3px 0;">
            <strong>Date:</strong>
            <span>{{ $sale && $sale->created_at ? $sale->created_at->format('m/d/Y h:i A') : 'N/A' }}</span>
        </div>
        <div class="d-flex justify-content-between" style="margin: 3px 0;">
            <strong>Invoice #:</strong>
            <span>{{ $sale && $sale->created_at ? $sale->created_at->format('m/d/Y h:i A') : 'N/A' }}</span>
        </div>
        <div class="d-flex justify-content-between" style="margin: 3px 0;">
            <strong>Customer:</strong>
            <span>
                @if($sale && $sale->customer)
                    {{ $sale->customer->first_name }} {{ $sale->customer->last_name }}
                @else
                    WALK-IN
                @endif
            </span>            
        </div>
        @if($sale && $sale->delivery === 'YES')
            <div class="d-flex justify-content-between" style="margin: 3px 0;">
                <strong>Delivery Address:</strong>
                <span>{{ $sale->customer_address }}</span>
            </div>
        @endif
        <div class="receipt-divider" style="border-top: 1px dashed #ccc; margin: 5px 0;"></div>
    </div>

    <!-- Items Table -->
    <table class="receipt-items" style="width: 100%; border-collapse: collapse; margin: 5px 0;">
        <thead>
            <tr>
                <th class="text-start" style="text-align: left; font-weight: bold; padding: 3px 0; border-bottom: 1px solid #eee;">Item</th>
                <th class="text-center" style="text-align: center; font-weight: bold; padding: 3px 0; border-bottom: 1px solid #eee;">Qty</th>
                <th class="text-end" style="text-align: right; font-weight: bold; padding: 3px 0; border-bottom: 1px solid #eee;">Price</th>
                <th class="text-end" style="text-align: right; font-weight: bold; padding: 3px 0; border-bottom: 1px solid #eee;">Total</th>
            </tr>
        </thead>
        <tbody>
            @if($sale && $sale->saleItems)
                @foreach($sale->saleItems as $item)
                    <tr>
                        <td class="text-start">{{ $item->product->name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                        <td class="text-end">₱{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">No sale items found.</td>
                </tr>
            @endif

        </tbody>
    </table>

    <!-- Summary -->
    <div class="receipt-summary" style="padding: 0 5px;">
        @if($sale && $sale->delivery === 'YES')
            <div class="d-flex justify-content-between">
                <span>Delivery Fee:</span>
                <span>₱{{ number_format($sale->delivery_fee, 2) }}</span>
            </div>
        @endif
        
        <!-- Gross Amount (VAT-inclusive before discount) -->
        <div class="d-flex justify-content-between">
            <span>Gross Amount:</span>
            <span>
                ₱{{ $sale ? number_format($sale->total_amount + $sale->discount, 2) : '0.00' }}
            </span>            
        </div>
        
        @if($sale && $sale->discount_type)
            <div class="d-flex justify-content-between">
                <span>Discount ({{ $sale->discount_type == 'senior/pwd' ? '20% Senior/PWD' : 'Custom' }}):</span>
                <span>-₱{{ number_format($sale->discount, 2) }}</span>
            </div>                                                              
        @endif
        
        <!-- VAT Breakdown -->
        <div class="receipt-divider" style="border-top: 1px dashed #ccc; margin: 5px 0;"></div>
        <div class="d-flex justify-content-between">
            <span>VATable Sales:</span>
            <span>₱{{ $sale ? number_format($sale->net_amount, 2) : '0.00' }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>VAT (12%):</span>
            <span>₱{{ $sale ? number_format($sale->vat_amount, 2) : '0.00' }}</span>
        </div>
        
        <!-- Final Total -->
        <div class="receipt-divider" style="border-top: 1px dashed #ccc; margin: 5px 0;"></div>
        <div class="d-flex justify-content-between receipt-total">
            <strong>Total Amount Due:</strong>
            <strong>₱{{ $sale ? number_format($sale->total_amount, 2) : '0.00' }}</strong>
        </div>
        
        <!-- Payment Details -->
        <div class="receipt-divider" style="border-top: 1px dashed #ccc; margin: 5px 0;"></div>
        <div class="d-flex justify-content-between">
            <span>Amount Paid:</span>
            <span>₱{{ $sale ? number_format($sale->paid_amount, 2) : '0.00' }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Change:</span>
            <span>₱{{ $sale ? number_format(abs($sale->sukli), 2) : '0.00' }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="receipt-footer text-center mt-2" style="padding: 5px 0;">
        <p class="receipt-thankyou" style="font-weight: bold; margin: 3px 0;">Thank you for your purchase!</p>
        <p class="receipt-note" style="font-style: italic; margin: 3px 0;">This receipt serves as proof of payment</p>
        <p class="receipt-vat" style="margin: 3px 0;">VAT Reg No: 123-456-789-000</p>
        <p class="receipt-serial" style="color: #666; margin: 3px 0 0 0;">{{ date('m/d/Y H:i:s') }}</p>
    </div>
</div>

<style>
    #invoice-POS {
        width: 90mm;
        margin: 0 auto;
        padding: 10px;
        font-family: 'Arial', sans-serif;
        font-size: 12px;
        color: #333;
        background: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        border-radius: 5px;
    }
    
    .receipt-divider {
        border-top: 1px dashed #ccc;
        margin: 5px 0;
    }
    
    .receipt-total {
        font-weight: bold;
        font-size: 13px;
    }

    @media print {
        #invoice-POS {
            box-shadow: none;
            border-radius: 0;
            width: 80mm;
            padding: 5px;
            font-size: 11px;
        }
        .receipt-total {
            font-size: 12px;
        }
    }
</style>