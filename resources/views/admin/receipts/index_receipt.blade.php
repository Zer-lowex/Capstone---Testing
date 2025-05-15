@extends('admin.layout')

@section('title', 'Receipt')

@section('content')
<h1 class="title">Receipt</h1>
<ul class="breadcrumbs">
    <li><a href="#">Home</a></li>
    <li class="divider">/</li>
    <li><a href="#" class="active">Orders</a></li>
</ul>

<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button id="btnPrint" class="btn btn-primary" onclick="PrintReceiptContent('invoice-POS')">
                <i class="bx bxs-printer"></i> Print
            </button>            
        </div>

        <!-- Receipt Content -->
        <div id="invoice-POS" style="width: 90mm; margin: 0 auto; padding: 10px; font-family: 'Arial'; font-size: 12px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.2); border-radius: 5px;">
            <!-- Header -->
            <div class="text-center mb-2">
                <img src="{{ asset('assets/images/kc.png') }}" alt="Logo" style="max-width: 50px; height: auto;">
                <h2 style="font-size: 14px; font-weight: bold; margin: 5px 0 0 0;">KC PRIME ENTERPRISE</h2>
                <p style="font-size: 10px; margin: 2px 0;">Dr. V Locsin Taclobo, Dumaguete City</p>
                <p style="font-size: 10px; margin: 2px 0 5px 0;">Phone: 0912-345-6789</p>
                <div style="border-top: 1px dashed #ccc; margin: 5px 0;"></div>
            </div>
        
            <!-- Customer Info -->
            <div class="mb-2">
                <div class="d-flex justify-content-between" style="margin: 3px 0;">
                    <strong>Date:</strong>
                    <span>{{ $sale->created_at->format('m/d/Y h:i A') }}</span>
                </div>
                <div class="d-flex justify-content-between" style="margin: 3px 0;">
                    <strong>Invoice #:</strong>
                    <span>{{ $sale->created_at->format('YmdHis') . $sale->id }}</span>
                </div>
                <div class="d-flex justify-content-between" style="margin: 3px 0;">
                    <strong>Customer:</strong>
                    <span>
                        @if($sale->customer)
                            {{ $sale->customer->first_name }} {{ $sale->customer->last_name }}
                        @else
                            WALK-IN
                        @endif
                    </span>
                </div>
                @if($sale->delivery === 'YES')
                <div class="d-flex justify-content-between" style="margin: 3px 0;">
                    <strong>Delivery Address:</strong>
                    <span>{{ $sale->customer_address }}</span>
                </div>
                @endif
                <div style="border-top: 1px dashed #ccc; margin: 5px 0;"></div>
            </div>
        
            <!-- Items Table -->
            <table style="width: 100%; border-collapse: collapse; margin: 5px 0;">
                <thead>
                    <tr>
                        <th style="text-align: left; font-weight: bold; padding: 3px 0; border-bottom: 1px solid #eee;">Item</th>
                        <th style="text-align: center; font-weight: bold; padding: 3px 0; border-bottom: 1px solid #eee;">Qty</th>
                        <th style="text-align: right; font-weight: bold; padding: 3px 0; border-bottom: 1px solid #eee;">Price</th>
                        <th style="text-align: right; font-weight: bold; padding: 3px 0; border-bottom: 1px solid #eee;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->saleItems as $item)
                    <tr>
                        <td style="text-align: left; padding: 3px 0; border-bottom: 1px solid #eee;">{{ $item->product->name }}</td>
                        <td style="text-align: center; padding: 3px 0; border-bottom: 1px solid #eee;">{{ $item->quantity }}</td>
                        <td style="text-align: right; padding: 3px 0; border-bottom: 1px solid #eee;">₱{{ number_format($item->price, 2) }}</td>
                        <td style="text-align: right; padding: 3px 0; border-bottom: 1px solid #eee;">₱{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        
            <!-- Summary -->
            <div style="padding: 0 5px;">
                @if($sale->delivery === 'YES')
                <div class="d-flex justify-content-between" style="margin: 3px 0;">
                    <span>Delivery Fee:</span>
                    <span>₱{{ number_format($sale->delivery_fee, 2) }}</span>
                </div>
                @endif
                
                <div class="d-flex justify-content-between" style="margin: 3px 0;">
                    <span>Gross Amount:</span>
                    <span>₱{{ number_format($sale->total_amount + $sale->discount, 2) }}</span>
                </div>
                
                @if($sale->discount_type)
                <div class="d-flex justify-content-between" style="margin: 3px 0;">
                    <span>Discount ({{ $sale->discount_type == 'senior/pwd' ? '20% Senior/PWD' : 'Custom' }}):</span>
                    <span>-₱{{ number_format($sale->discount, 2) }}</span>
                </div>
                @endif
                
                <div style="border-top: 1px dashed #ccc; margin: 5px 0;"></div>
                
                <div class="d-flex justify-content-between" style="margin: 3px 0;">
                    <span>VATable Sales:</span>
                    <span>₱{{ number_format($sale->net_amount, 2) }}</span>
                </div>
                
                <div class="d-flex justify-content-between" style="margin: 3px 0;">
                    <span>VAT (12%):</span>
                    <span>₱{{ number_format($sale->vat_amount, 2) }}</span>
                </div>
                
                <div style="border-top: 1px dashed #ccc; margin: 5px 0;"></div>
                
                <div class="d-flex justify-content-between" style="margin: 5px 0; font-size: 13px; font-weight: bold;">
                    <span>Total Amount:</span>
                    <span>₱{{ number_format($sale->total_amount, 2) }}</span>
                </div>
                
                <div class="d-flex justify-content-between" style="margin: 3px 0;">
                    <span>Amount Paid:</span>
                    <span>₱{{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                
                <div class="d-flex justify-content-between" style="margin: 3px 0; font-weight: bold;">
                    <span>Change:</span>
                    <span>₱{{ number_format(abs($sale->sukli), 2) }}</span>
                </div>
            </div>
        
            <!-- Footer -->
            <div class="text-center mt-2">
                <p style="font-weight: bold; margin: 3px 0;">Thank you for your purchase!</p>
                <p style="font-style: italic; margin: 3px 0;">This receipt serves as proof of payment</p>
                <p style="margin: 3px 0;">VAT Reg No: 123-456-789-000</p>
                <p style="color: #666; margin: 3px 0 0 0;">{{ date('m/d/Y H:i:s') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
function PrintReceiptContent(el) {
    var content = document.getElementById(el).innerHTML;
    var originalContent = document.body.innerHTML;
    
    // Create a print window
    var printWindow = window.open('', '', 'width=80mm,height=auto');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print Receipt</title>
            <style>
                body {
                    font-family: Arial;
                    font-size: 12px;
                    padding: 0;
                    margin: 0;
                    width: 80mm;
                }
                #invoice-POS {
                    padding: 10px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    padding: 3px 0;
                }
                .d-flex {
                    display: flex;
                }
                .justify-content-between {
                    justify-content: space-between;
                }
                .text-center {
                    text-align: center;
                }
                .text-right {
                    text-align: right;
                }
                .mb-2 {
                    margin-bottom: 10px;
                }
                .mt-2 {
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            ${content}
            <script>
                window.onload = function() {
                    setTimeout(function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 100);
                    }, 50);
                };
            <\/script>
        </body>
        </html>
    `);
    
    printWindow.document.close();
}
</script>

@endsection