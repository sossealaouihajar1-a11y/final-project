# Vendor Features Documentation

## Overview

This document outlines the comprehensive vendor management system added to the Final Project. It includes complete CRUD operations for products, client tracking, order management, and inventory control.

## Features Added

### 1. **Product Management (CRUD)**

#### Backend Endpoints:
- `GET /api/vendor/products` - List all vendor's products with pagination and filters
- `POST /api/vendor/products` - Create a new product
- `GET /api/vendor/products/{id}` - Get product details
- `PUT /api/vendor/products/{id}` - Update product information
- `DELETE /api/vendor/products/{id}` - Delete a product
- `GET /api/vendor/products/statistics` - Get product statistics
- `GET /api/vendor/products/categories-list` - Get available categories
- `POST /api/vendor/products/bulk-status` - Update multiple products status
- `POST /api/vendor/products/{id}/stock` - Update product stock

#### Features:
- Create, read, update, delete vintage products
- Filter by category, status, condition
- Search products by title or description
- Track product statistics (total, active, low stock, out of stock)
- Bulk status updates
- Stock management per product

#### Frontend:
- **ProductsView.vue** - Full CRUD interface with modal forms
- Product list with sorting and filtering
- Add/Edit product modal
- Delete confirmation
- Stock status indicators

---

### 2. **Client Tracking (Suivi Clients)**

#### Backend Endpoints:
- `GET /api/vendor/clients` - List all clients who purchased from vendor
- `GET /api/vendor/clients/{clientId}` - Get client details and purchase history
- `GET /api/vendor/clients/statistics` - Get client statistics
- `GET /api/vendor/clients/recent-orders` - Get recent orders from clients
- `GET /api/vendor/clients/export` - Export client list as CSV

#### Features:
- View all clients who have made purchases
- Search clients by name, email, or phone
- Track client purchase history
- Calculate client statistics:
  - Total purchases count
  - Total amount spent
  - Last purchase date
  - Average order value
- Identify repeat customers
- Export client list to CSV
- View detailed client information with all their orders

#### Frontend:
- **ClientsView.vue** - Complete client management interface
- Client listing with search functionality
- Client statistics cards
- Detailed client modal showing:
  - Purchase history
  - Total spent
  - Order status
  - Contact information

---

### 3. **Order Tracking (Suivi des Commandes)**

#### Backend Endpoints:
- `GET /api/vendor/orders` - List all orders containing vendor's products
- `GET /api/vendor/orders/{orderId}` - Get order details
- `PUT /api/vendor/orders/{orderId}/status` - Update order status
- `GET /api/vendor/orders/statistics` - Get order statistics
- `GET /api/vendor/orders/status/{status}` - Get orders by status
- `GET /api/vendor/orders/export` - Export orders as CSV

#### Features:
- View all orders containing vendor's products
- Filter orders by status, date range, and client name
- Update order status (pending → confirmed → shipped → delivered)
- Track order statistics:
  - Total orders
  - Orders by status breakdown
  - Total items sold
  - Total revenue
- Export orders to CSV
- View detailed order information with items and shipping address

#### Order Statuses:
- **pending** - Initial status
- **confirmed** - Order confirmed by vendor
- **shipped** - Order has been shipped
- **delivered** - Order delivered to customer
- **cancelled** - Order cancelled

#### Frontend:
- **OrdersView.vue** - Complete order management interface
- Order statistics with status breakdown
- Order listing with filters
- Status update modal
- Order details modal
- Export functionality

---

### 4. **Stock Management (Gestion du Stock)**

#### Backend Endpoints:
- `GET /api/vendor/stock` - Get stock overview with filters
- `POST /api/vendor/stock` - Create stock movement record
- `PUT /api/vendor/stock/{productId}` - Update product stock
- `POST /api/vendor/stock/{productId}/adjust` - Adjust stock with reason
- `POST /api/vendor/stock/bulk-update` - Update multiple products stock
- `GET /api/vendor/stock/statistics` - Get inventory statistics
- `GET /api/vendor/stock/low-stock` - Get low stock products
- `GET /api/vendor/stock/out-of-stock` - Get out of stock products
- `GET /api/vendor/stock/alerts` - Get stock alerts
- `GET /api/vendor/stock/history` - Get stock movement history
- `GET /api/vendor/stock/export` - Export stock report as CSV

#### Features:
- Real-time stock level tracking
- Low stock alerts (< 5 items)
- Out of stock tracking
- Inventory value calculation (stock × price)
- Stock adjustment with reasons:
  - Restock
  - Damage
  - Correction
  - Return
  - Other
- Stock movement history logging
- Bulk stock updates
- Advanced filtering by stock level
- Export stock reports

#### Frontend:
- **StockView.vue** - Comprehensive inventory management
- Stock overview with statistics
- Alert system for low/out of stock
- Stock filtering and search
- Edit stock modal
- Adjust stock modal with reason tracking
- Inventory value tracking

---

## Database Schema

### New Tables:
```sql
-- Stock Movements Table
CREATE TABLE stock_movements (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  product_id UUID NOT NULL,
  quantity INT NOT NULL,
  reason ENUM('restock', 'sale', 'damage', 'return', 'correction', 'bulk_update', 'manual', 'other'),
  notes TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES vintage_products(id)
);
```

---

## API Response Examples

### Products List Response:
```json
{
  "data": [
    {
      "id": "uuid",
      "title": "Vintage Chair",
      "description": "Beautiful vintage chair",
      "category": "furniture",
      "price": "150.00",
      "promotion": "10.00",
      "condition": "good",
      "stock": 5,
      "status": "active",
      "image_url": "url",
      "created_at": "2025-01-06T00:00:00Z"
    }
  ],
  "links": {},
  "meta": {}
}
```

### Client Details Response:
```json
{
  "client": {
    "id": "uuid",
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "123456789",
    "city": "Paris"
  },
  "orders": [
    {
      "id": "uuid",
      "total_price": "300.00",
      "status": "delivered",
      "items": [
        {
          "id": "uuid",
          "quantity": 2,
          "total_price": "300.00",
          "product": {}
        }
      ]
    }
  ],
  "statistics": {
    "total_purchases": 5,
    "total_spent": "1500.00",
    "average_order_value": "300.00",
    "last_purchase": "2025-01-05T00:00:00Z"
  }
}
```

---

## Frontend Routes

```javascript
/vendor/dashboard      - Main dashboard with overview
/vendor/products       - Product CRUD management
/vendor/clients        - Client tracking
/vendor/orders         - Order tracking and management
/vendor/stock          - Inventory management
```

---

## Security Notes

All vendor endpoints are protected by:
1. Authentication middleware (`auth:sanctum`)
2. Vendor approval middleware (`vendor.approved`)
3. Data isolation - vendors can only access their own data

---

## File Structure

### Backend Controllers:
```
backend/app/Http/Controllers/Api/Vendor/
├── ProductController.php      # Product CRUD
├── ClientController.php        # Client tracking
├── OrderController.php         # Order management
└── StockController.php         # Stock management
```

### Frontend Components:
```
frontend/src/
├── views/vendor/
│   ├── DashboardView.vue       # Main layout with sidebar
│   ├── ProductsView.vue        # Product management
│   ├── ClientsView.vue         # Client tracking
│   ├── OrdersView.vue          # Order tracking
│   └── StockView.vue           # Stock management
├── components/vendor/
│   ├── StatsCard.vue           # Stats display component
│   └── StatusBar.vue           # Progress bar component
└── router/index.js             # Updated with vendor routes
```

---

## Usage Examples

### Create a Product:
```bash
curl -X POST http://localhost/api/vendor/products \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Vintage Clock",
    "description": "Beautiful antique clock",
    "category": "furniture",
    "price": 200,
    "stock": 10,
    "condition": "good"
  }'
```

### Adjust Stock:
```bash
curl -X POST http://localhost/api/vendor/stock/PRODUCT_ID/adjust \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "adjustment": 5,
    "reason": "restock",
    "notes": "Received new shipment"
  }'
```

### Update Order Status:
```bash
curl -X PUT http://localhost/api/vendor/orders/ORDER_ID/status \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "shipped",
    "notes": "Package sent via DHL"
  }'
```

---

## Future Enhancements

1. **Analytics Dashboard**
   - Sales charts and graphs
   - Revenue tracking over time
   - Customer acquisition metrics

2. **Notifications**
   - Stock alerts
   - New order notifications
   - Customer reviews/ratings

3. **Automation**
   - Automatic reorder points
   - Bulk operations scheduling
   - Email notifications

4. **Advanced Filtering**
   - Date range filters
   - Advanced search with operators
   - Saved filter presets

5. **Reporting**
   - Custom report generation
   - Scheduled reports
   - Email reports

---

## Testing

All endpoints have been tested with various filters and edge cases. Test the features:

1. Create products with different statuses
2. Filter products by category/status
3. Track client purchase history
4. Update order statuses
5. Adjust stock with different reasons
6. Export data in CSV format

---

## Maintenance

- Regularly backup stock movement history
- Archive old orders periodically
- Monitor low stock alerts
- Review client activity patterns

