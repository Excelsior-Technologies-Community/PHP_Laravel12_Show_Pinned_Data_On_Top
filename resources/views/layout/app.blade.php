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

        table th,
        table td {
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

        input,
        textarea,
        select {
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

        .price-label {
            font-size: 13px;
            color: #6b7280;
            margin-top: 15px;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .related-products-section {
            margin-top: 50px;
        }

        .related-products-section h3 {
            margin-bottom: 20px;
        }

        /* =========================================================
           PIN MANAGEMENT
        ========================================================= */

        .page-subtitle {
            margin: 5px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .page-header h2 {
            margin-bottom: 0;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* ================= ALERTS ================= */

        .alert {
            padding: 12px 15px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert ul {
            margin: 8px 0 0 20px;
        }

        /* ================= PIN SETTINGS ================= */

        .pin-settings {
            margin-top: 25px;
            padding: 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
        }

        .pin-settings h3 {
            margin-top: 0;
            margin-bottom: 15px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .checkbox-label input {
            width: auto;
        }

        .pin-settings-fields {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .pin-settings small {
            display: block;
            margin-top: 5px;
            color: #6b7280;
        }

        /* ================= PIN STATUS ================= */

        .status-badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-badge.pinned {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.scheduled {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.expired {
            background: #fee2e2;
            color: #991b1b;
        }

        .small-link {
            display: inline-block;
            margin-top: 5px;
            font-size: 12px;
            color: #2563eb;
            text-decoration: none;
        }

        .small-link:hover {
            text-decoration: underline;
        }

        .priority-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 12px;
        }

        .schedule-item {
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .frontend-header {
            margin-bottom: 25px;
        }

        /* ================= PINNED PRODUCT CARD ================= */

        .featured-product {
            position: relative;
            border: 2px solid #facc15;
        }

        .pinned-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #facc15;
            color: #000;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,.2);
            z-index: 10;
        }

        .priority-card-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #1f2937;
            color: #fff;
            padding: 5px 8px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 5px;
            z-index: 10;
        }

        /* =========================================================
           STATISTICS DASHBOARD
        ========================================================= */

        .statistics-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 25px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: #f3f4f6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-card p {
            margin: 0 0 5px;
            color: #6b7280;
            font-size: 13px;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 26px;
        }

        .pinned-stat {
            border-color: #fde68a;
        }

        .active-stat {
            border-color: #bbf7d0;
        }

        .scheduled-stat {
            border-color: #fed7aa;
        }

        .expired-stat {
            border-color: #fecaca;
        }

        /* ================= PINNING PERCENTAGE ================= */

        .percentage-card {
            margin-top: 25px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
        }

        .percentage-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .percentage-header h3 {
            margin: 0;
        }

        .percentage-header strong {
            font-size: 24px;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            margin-top: 15px;
            background: #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #2563eb;
            border-radius: 20px;
            transition: width .4s ease;
        }

        .percentage-card p {
            margin-bottom: 0;
            color: #6b7280;
            font-size: 13px;
        }

        /* ================= TOP PRIORITY PRODUCTS ================= */

        .top-products-section {
            margin-top: 30px;
        }

        .top-products-section h3 {
            margin-bottom: 15px;
        }

        .empty-text {
            text-align: center;
            padding: 30px;
            color: #6b7280;
        }

        .clean-table {
            overflow-x: auto;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 992px) {

            .statistics-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .container {
                margin: 20px;
            }

            .clean-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        @media (max-width: 768px) {

            .nav {
                padding: 15px;
            }

            .nav a {
                display: inline-block;
                margin-bottom: 8px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
                flex-wrap: wrap;
            }

            .statistics-grid {
                grid-template-columns: 1fr;
            }

            .product-detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-image-box img {
                height: 300px;
            }

            table thead {
                display: none;
            }

            table,
            table tbody,
            table tr,
            table td {
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

            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .image-preview-wrapper {
                flex-direction: column;
            }

            .card-footer {
                gap: 10px;
            }
        }
    </style>
</head>

<body>

<!-- ================= NAVBAR ================= -->

<div class="nav">

    <a href="{{ route('frontend.products') }}">
        Products
    </a>

    <a href="{{ route('categories.index') }}">
        Categories
    </a>

    <a href="{{ route('product.index') }}">
        Admin Products
    </a>

    <a href="{{ route('product.statistics') }}">
        📊 Pin Statistics
    </a>

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

            preview.innerHTML =
                `<img src="${e.target.result}">`;

        };

        reader.readAsDataURL(input.files[0]);

    }

}

</script>

</body>
</html>