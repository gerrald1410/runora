<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Runora</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #666;
        }
        .stat-card .value {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .nav {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .nav a {
            margin-right: 20px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard Runora</h1>
            <p>Selamat datang, {{ Auth::user()->name ?? Auth::user()->email }}!</p>
            <a href="/logout" class="logout-btn">Logout</a>
        </div>
        
        <div class="nav">
            <a href="/admin/dashboard">Dashboard</a>
            <a href="/admin/products">Produk</a>
            <a href="/shop">Toko</a>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Total Produk</h3>
                <div class="value">{{ $totalProduk }}</div>
            </div>
            <div class="stat-card">
                <h3>Produk Diskon</h3>
                <div class="value">{{ $totalDiskon }}</div>
            </div>
        </div>
        
        <div class="stat-card">
            <h3>Informasi Sistem</h3>
            <p>Anda berhasil login sebagai <strong>Admin</strong></p>
            <p>Email: {{ Auth::user()->email }}</p>
            <p>Role: {{ Auth::user()->role ?? 'admin' }}</p>
        </div>
    </div>
</body>
</html>