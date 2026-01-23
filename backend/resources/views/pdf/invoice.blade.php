<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid  #4a1709;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 24px;
            margin: 20px 0;
        }
        .info-section {
            margin: 20px 0;
        }
        .info-box {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .info-box h3 {
            margin-top: 0;
            color: #4a1709;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        th {
            background: #4a1709;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 30px;
            text-align: right;
        }
        .totals table {
            margin-left: auto;
            width: 300px;
        }
        .totals th, .totals td {
            background: none;
            color: #333;
        }
        .total-line {
            background: #431502 !important;
            color: white !important;
            font-size: 18px;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Nostalgia Collective</div>
        <div>Boutique d'Articles Vintage</div>
        <div style="margin-top: 10px; font-size: 12px; color: #6b7280;">
            contact@nostalgia-collective.com | +33 1 23 45 67 89
        </div>
    </div>

    <h1 class="invoice-title">FACTURE</h1>

    <div class="info-section">
        <div class="info-box">
            <h3>📋 Informations Facture</h3>
            <p>
                <strong>N° Facture:</strong> INV-{{ date('Y') }}-{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}<br>
                <strong>N° Commande:</strong> {{ $order->id }}<br>
                <strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}<br>
            
            </p>
        </div>

        <div class="info-box" style="float: right;">
            <h3>👤 Client</h3>
            <p>
                <strong>{{ $order->user->name }}</strong><br>
                {{ $order->user->email }}<br>
                @if($order->shippingAddress)
                    {{ $order->shippingAddress->phone }}
                @endif
            </p>
        </div>
    </div>

    @if($order->shippingAddress)
    <div style="clear: both; margin-top: 20px;">
        <div class="info-box" style="width: 100%;">
            <h3>📍 Adresse de Livraison</h3>
            <p>
                <strong>{{ $order->shippingAddress->full_name }}</strong><br>
                {{ $order->shippingAddress->address }}<br>
                {{ $order->shippingAddress->postal_code }} {{ $order->shippingAddress->city }}<br>
                {{ $order->shippingAddress->country }}<br>
                Tél: {{ $order->shippingAddress->phone }}
            </p>
        </div>
    </div>
    @endif

    <h2 style="margin-top: 40px; color: #667eea;">Articles Commandés</h2>
    
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Catégorie</th>
                <th class="text-right">Prix Unit.</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td><strong>{{ $item->vintageProduct->title }}</strong></td>
                <td>{{ $item->vintageProduct->category }}</td>
                <td class="text-right">{{ number_format($item->price, 2) }}MAD</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right"><strong>{{ number_format($item->subtotal, 2) }}MAD</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
    <table>
        <tr>
            <th>Sous-total:</th>
            <td class="text-right">{{ number_format($order->invoice->subtotal, 2) }}MAD</td>
        </tr>
        <tr>
            <th>Frais de port:</th>
            <td class="text-right" :style="$order->invoice->shipping_cost == 0 ? 'color: #10b981; font-weight: bold;' : ''">
                @if($order->invoice->shipping_cost == 0)
                    GRATUIT
                @else
                    {{ number_format($order->invoice->shipping_cost, 2) }}MAD
                @endif
            </td>
        </tr>
        <tr class="total-line">
            <th>TOTAL TTC:</th>
            <td class="text-right">{{ number_format($order->invoice->total_amount, 2) }}MAD</td>
        </tr>
    </table>
</div>

    <div class="footer">
        <p><strong>Merci pour votre confiance !</strong></p>
        <p>Nostalgia Collective - SIRET: 123 456 789 00010 - TVA: FR12345678901</p>
        <p>123 Rue du Vintage, 75001 Fes, Maroc</p>
    </div>
</body>
</html>