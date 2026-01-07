# Vendor Features - Quick Start Guide

## 🚀 Setup Instructions

### Step 1: Run Database Migration

```bash
cd backend
php artisan migrate
```

This creates the `stock_movements` table needed for inventory tracking.

### Step 2: Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:cache
```

### Step 3: Verify Routes

Check that vendor routes are registered:
```bash
php artisan route:list | grep vendor
```

You should see routes like:
- `POST|GET /api/vendor/products`
- `POST|GET /api/vendor/clients`
- `POST|GET /api/vendor/orders`
- `POST|GET /api/vendor/stock`

---

## 📖 Usage Guide

### Accessing Vendor Features

1. **Login as Vendor**
   - Go to `/login`
   - Use vendor credentials
   - Vendor must be approved to access features

2. **Navigate to Dashboard**
   - URL: `http://localhost:5173/vendor/dashboard`
   - Or use sidebar navigation after login

### Dashboard Sections

#### 1. **Dashboard** (`/vendor/dashboard`)
- Overview statistics
- Order status breakdown
- Top products
- Quick metrics

#### 2. **Products** (`/vendor/products`)
- View all your products
- **Add Product:**
  - Click "+ Ajouter un produit"
  - Fill form: title, description, category, price, stock
  - Click "Ajouter"
- **Edit Product:**
  - Click "✏️ Modifier" on any product
  - Update fields
  - Click "Mettre à jour"
- **Delete Product:**
  - Click "🗑️ Supprimer"
  - Confirm deletion
- **Filter Products:**
  - Use search bar
  - Filter by category
  - Filter by status
  - Click "Filtrer"

#### 3. **Clients** (`/vendor/clients`)
- View clients who purchased from you
- **Search Clients:**
  - Search by name, email, or phone
  - Click "Rechercher"
- **View Client Details:**
  - Click "👁️ Voir" on any client
  - See purchase history
  - View spending statistics
- **Export Clients:**
  - Click "📥 Exporter"
  - Downloads CSV file

**Client Statistics:**
- Total clients
- Clients who purchased multiple times
- Total orders
- Total revenue

#### 4. **Orders** (`/vendor/orders`)
- View all orders containing your products
- **Filter Orders:**
  - Search by client name
  - Filter by status
  - Filter by date range
  - Click "Filtrer"
- **View Order Details:**
  - Click "👁️ Voir"
  - See items, client info, total
- **Update Order Status:**
  - Click "⬆️ Mettre à jour"
  - Select new status:
    - En attente (pending)
    - Confirmée (confirmed)
    - Expédiée (shipped)
    - Livrée (delivered)
    - Annulée (cancelled)
  - Add optional notes
  - Click "Mettre à jour"
- **Export Orders:**
  - Click "📥" button
  - Downloads CSV file

**Order Statistics:**
- Total orders count
- Orders by status breakdown
- Total items sold
- Total revenue

#### 5. **Stock** (`/vendor/stock`)
- Manage inventory levels
- **View Stock:**
  - See all products with current stock
  - Color-coded stock levels:
    - 🔴 Red (0 items)
    - 🟡 Yellow (1-4 items)
    - 🟢 Green (5+ items)
- **Edit Stock:**
  - Click "✏️ Modifier"
  - Enter new stock value
  - Select reason (optional)
  - Add notes (optional)
  - Click "Mettre à jour"
- **Adjust Stock:**
  - Click "⚖️ Ajuster"
  - Enter adjustment (+5 or -3)
  - Select reason:
    - Réapprovisionnement (restock)
    - Dommage (damage)
    - Correction (correction)
    - Retour (return)
    - Autre (other)
  - Add notes
  - Click "Ajuster"
- **Filter Stock:**
  - Filter by stock level
  - Search products
  - Click "Filtrer"
- **Stock Alerts:**
  - Yellow alert bar shows low/out of stock items
  - Click "⚠️" icon to see affected products
- **Export Stock:**
  - Click "📥 Exporter"
  - Downloads CSV with inventory report

**Stock Statistics:**
- Total items in stock
- Inventory value
- Low stock count
- Out of stock count

---

## 🔑 Keyboard Shortcuts

| Action | Shortcut |
|--------|----------|
| Close Modal | ESC |
| Search | Ctrl+F (browser) |
| Filter | Enter (in filter fields) |

---

## 📊 Common Tasks

### Task 1: Add a New Product
```
1. Go to /vendor/products
2. Click "+ Ajouter un produit"
3. Fill in details
4. Click "Ajouter"
```

### Task 2: Check Stock Levels
```
1. Go to /vendor/stock
2. Look at color-coded stock levels
3. Check alerts for low/out of stock
4. Adjust as needed
```

### Task 3: Process an Order
```
1. Go to /vendor/orders
2. Find order (search if needed)
3. Click "⬆️ Mettre à jour"
4. Change status to "Confirmée"
5. Add tracking info in notes
6. Later, update to "Expédiée"
7. Finally, update to "Livrée"
```

### Task 4: Track Customer History
```
1. Go to /vendor/clients
2. Search for customer
3. Click "👁️ Voir"
4. See all their orders
5. View spending stats
```

### Task 5: Export Data
```
1. Go to relevant section (products/clients/orders/stock)
2. Apply filters if needed
3. Click export button (📥)
4. Open CSV in Excel/Sheets
```

---

## 🐛 Troubleshooting

### Issue: Can't access vendor routes
**Solution:**
- Ensure you're logged in as vendor
- Check if vendor account is approved
- Clear browser cache and refresh

### Issue: Products not showing
**Solution:**
- Make sure products are assigned to your vendor ID
- Check database connection
- Verify migration ran successfully

### Issue: Stock alerts not showing
**Solution:**
- Ensure `stock_movements` table exists
- Check product stock values
- Refresh page

### Issue: Export not working
**Solution:**
- Check browser pop-up settings
- Ensure adequate disk space
- Try different browser

---

## 📱 Mobile Access

All vendor features are mobile-responsive:
- Sidebar collapses on mobile
- Tables become scrollable
- Modals adapt to screen size
- Touch-friendly buttons

---

## 🔒 Security Notes

- All data is encrypted in transit (HTTPS)
- Authentication tokens expire after inactivity
- Relogin if session expires
- Don't share your token
- Logout when done

---

## 📞 Support

If you encounter issues:
1. Check browser console for errors (F12)
2. Review server logs: `storage/logs/laravel.log`
3. Verify API endpoints are responding
4. Check database connectivity

---

## 🎯 Best Practices

1. **Inventory Management:**
   - Regularly update stock levels
   - Add notes when adjusting inventory
   - Monitor low stock alerts
   - Export reports monthly

2. **Order Management:**
   - Update order status promptly
   - Include tracking information
   - Respond to customer inquiries
   - Keep accurate shipping notes

3. **Product Management:**
   - Use clear product titles
   - Write detailed descriptions
   - Set accurate prices
   - Update categories consistently
   - Keep product images current

4. **Client Relations:**
   - Monitor repeat customers
   - Track customer spending patterns
   - Export client lists for campaigns
   - Follow up on large orders

---

## 📈 Key Metrics to Track

**Daily:**
- New orders
- Stock levels
- Low stock alerts

**Weekly:**
- Total revenue
- Order completion rate
- Customer acquisition

**Monthly:**
- Total customers
- Average order value
- Inventory turnover
- Product performance

---

## 🔄 Regular Maintenance

**Daily:**
- Check orders for status updates
- Monitor stock alerts
- Process new orders

**Weekly:**
- Review bestselling products
- Check for inventory issues
- Analyze customer patterns

**Monthly:**
- Export and review reports
- Update product listings
- Optimize inventory levels
- Plan new products

---

## 📚 Additional Resources

- See `VENDOR_FEATURES.md` for detailed API documentation
- See `IMPLEMENTATION_SUMMARY.md` for technical overview
- Check backend logs for error details
- Review Vue components for customization

---

**Last Updated:** January 6, 2026

Happy Selling! 🎉
