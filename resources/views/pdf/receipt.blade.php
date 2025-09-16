<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo #{{ $sale->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .items { margin-bottom: 20px; }
        .item { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #eee; }
        .total { text-align: right; font-weight: bold; font-size: 14px; }
        .payment { text-align: center; margin: 10px 0; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>EL TURRIL POS - Recibo</h2>
        <p>Pedido #{{ $sale->id }} | {{ $sale->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="items">
        @foreach ($sale->saleItems as $item)
            <div class="item">
                <span>{{ $item->quantity }} x {{ $item->product->name }}</span>
                <span>{{ number_format($item->subtotal, 2) }} Bs.</span>
            </div>
        @endforeach
    </div>

    <div class="payment">
        Método de Pago: {{ $sale->payment_method }}
    </div>

    <div class="total">
        Total: {{ number_format($sale->final_amount, 2) }} Bs.
    </div>

    <div class="footer">
        Gracias por tu compra. ¡Vuelve pronto!
    </div>
</body>
</html>