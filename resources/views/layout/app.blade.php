<!DOCTYPE html>
<html>
<head>
    <title>Laravel Shop</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f1f3f6;
            color: #333;
        }

        /* ================= NAVBAR ================= */
        .nav {
            background: #1f2937;
            padding: 15px 30px;
        }

        .nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 20px;
            font-weight: 600;
        }

        .nav a:hover {
            text-decoration: underline;
        }

        /* ================= CONTAINER ================= */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,.08);
        }

        h2 {
            margin-bottom: 20px;
        }

        /* ================= BUTTONS ================= */
        .btn {
            padding: 7px 14px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-danger {
            background: #dc2626;
            color: #fff;
        }

        .btn-secondary {
            background: #6b7280;
            color: #fff;
        }

        .btn-light {
            background: #e5e7eb;
            color: #000;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* ================= TABLE (ADMIN) ================= */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
        }

        table th {
            background: #f9fafb;
            font-weight: 600;
        }

        table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        /* ================= FORM ================= */
        .form-group {
            margin-bottom: 15px;
        }

        input, textarea, select {
            width: 100%;
            padding: 9px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 90px;
        }

        .form-actions {
            margin-top: 20px;
        }

        /* ================= IMAGE PREVIEW ================= */
        .image-preview-wrapper {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .preview-box {
            width: 140px;
            height: 140px;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
        }

        .preview-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .preview-text {
            font-size: 13px;
            color: #6b7280;
        }

        /* ================= FRONTEND PRODUCT GRID ================= */
        .frontend-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 25px;
        }

        /* ================= MODERN PRODUCT CARD ================= */
        .product-card.modern {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0,0,0,.1);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .product-card.modern:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 30px rgba(0,0,0,.18);
        }

        .image-wrap {
            width: 100%;
            height: 200px;
            background: #f1f5f9;
        }

        .image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 14px;
        }

        .card-body {
            padding: 15px;
        }

        .product-title {
            margin: 0 0 6px;
            font-size: 17px;
            font-weight: 600;
        }

        .category {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .details {
            font-size: 14px;
            margin-bottom: 12px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            font-size: 18px;
            font-weight: 700;
            color: #2563eb;
        }

        /* ================= PRODUCT DETAIL PAGE ================= */
        .product-detail-wrapper {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
        }

        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: flex-start;
        }

        .detail-image-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
        }

        .detail-image-box img {
            width: 100%;
            height: 420px;
            object-fit: contain;
        }

        .detail-title {
            margin-top: 0;
            font-size: 26px;
            font-weight: 600;
        }

        .detail-category {
            color: #6b7280;
            margin: 10px 0;
        }

        .detail-price {
            font-size: 26px;
            font-weight: 700;
            color: #2563eb;
            margin: 15px 0;
        }

        .detail-description {
            margin-top: 20px;
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            line-height: 1.6;
        }

        .detail-actions {
            margin-top: 25px;
            display: flex;
            gap: 15px;
        }
        .price-label{
            font-size:13px;
            color:#6b7280;
            margin-top:15px;
            margin-bottom:2px;
            text-transform:uppercase;
            letter-spacing:0.5px;
        }
        .related-products-section{
            margin-top:50px;
        }

        .related-products-section h3{
            margin-bottom:20px;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-image-box img {
                height: 300px;
            }

            table thead {
                display: none;
            }

            table, table tbody, table tr, table td {
                display: block;
                width: 100%;
            }

            table tr {
                margin-bottom: 15px;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                padding: 10px;
            }

            table td {
                border: none;
                padding: 8px 0;
            }
        }
    </style>
</head>
<body>

<!-- ================= NAVBAR ================= -->
<div class="nav">
    <a href="{{ route('frontend.products') }}">Products</a>
    <a href="{{ route('categories.index') }}">Categories</a>
    <a href="{{ route('product.index') }}">Admin Products</a>
</div>

<!-- ================= CONTENT ================= -->
<div class="container">
    @yield('content')
</div>

<!-- ================= IMAGE PREVIEW SCRIPT ================= -->
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.innerHTML = `<img src="${e.target.result}">`;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>
