<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .order-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .item {
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .item:last-child {
            border-bottom: none;
        }
        .total {
            background: #667eea;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            background: #fef3c7;
            color: #92400e;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Commande Confirmée !</h1>
            <p>Merci pour votre confiance</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $order->user->name }}</strong>,</p>
            
            <p>Votre commande a bien été <strong>confirmée</strong> et est en cours de traitement.</p>
            
            <div style="background: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 0; color: #1e40af;">
                    <strong>📌 Note importante:</strong> Votre commande n'a pas encore été reçue par nos équipes, mais elle est bien enregistrée dans notre système. Vous recevrez une notification dès sa réception.
                </p>
            </div>
            
            <div class="order-details">
                <h2 style="color: #667eea; margin-top: 0;">📦 Détails de la commande</h2>
                
                <p><strong>N° de commande:</strong> {{ $order->id }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                <p>
                    <strong>Statut:</strong> 
                    <span class="status-badge">{{ ucfirst($order->status) }} - En attente</span>
                </p>
                
                <h3 style="margin-top: 25px; color: #667eea;">Articles commandés:</h3>
                @foreach($order->orderItems as $item)
                <div class="item">
                    <strong>{{ $item->vintageProduct->title }}</strong><br>
                    <span style="color: #6b7280;">{{ $item->vintageProduct->category }}</span><br>
                    Quantité: {{ $item->quantity }} × {{ number_format($item->price, 2) }}MAD
                    = <strong>{{ number_format($item->subtotal, 2) }}MAD</strong>
                </div>
                @endforeach
                
                @if($order->shippingAddress)
                <h3 style="margin-top: 25px; color: #667eea;">📍 Adresse de livraison:</h3>
                <p style="margin: 10px 0;">
                    {{ $order->shippingAddress->full_name }}<br>
                    {{ $order->shippingAddress->address }}<br>
                    {{ $order->shippingAddress->postal_code }} {{ $order->shippingAddress->city }}<br>
                    {{ $order->shippingAddress->country }}<br>
                    Tél: {{ $order->shippingAddress->phone }}
                </p>
                @endif
                
                <div class="total">
                    Total: {{ number_format($order->total_price, 2) }}MAD
                </div>
            </div>
            
            <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 0; font-size: 14px; color: #4b5563;">
                    <strong>📄 Facture:</strong> Votre facture est disponible en pièce jointe de cet email au format PDF.
                </p>
            </div>
            
            <h3 style="color: #667eea;">🔔 Prochaines étapes:</h3>
            <ol style="color: #4b5563;">
                <li>Nous recevrons votre commande dans les prochaines heures</li>
                <li>Votre commande sera préparée avec soin</li>
                <li>Vous recevrez un email avec le numéro de suivi</li>
                <li>Livraison sous 3 à 5 jours ouvrés</li>
            </ol>
            
            <p style="margin-top: 30px;">Si vous avez des questions concernant votre commande, n'hésitez pas à nous contacter à <a href="mailto:contact@vintageshop.com" style="color: #667eea;">contact@vintageshop.com</a></p>
            
            <p style="margin-top: 30px;">Cordialement,<br><strong>L'équipe Vintage Shop</strong></p>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb; text-align: center; font-size: 12px; color: #6b7280;">
                <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                <p>Nostalgia Collective - 123 Rue du Vintage, 75001 Fes, Maroc</p>
            </div>
        </div>
    </div>
</body>
</html>