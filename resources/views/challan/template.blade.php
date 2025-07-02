<!DOCTYPE html>
<html>
<head>
    <title>STMU Challan</title>
    <style>
        @page {
            size: A3 landscape;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .challan-container {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        .copy {
            width: 33.33%;
            padding: 10px;
            border-right: 1px dashed #000;
            box-sizing: border-box;
            position: relative;
        }

        .copy:last-child {
            border-right: none;
        }

        .copy-label {
            position: absolute;
            top: 5px;
            left: 10px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Include your challan_content styles */
    </style>
</head>
<body>
    <div class="challan-container">

        <!-- Parent Copy -->
        <div class="copy">
            {{-- <div class="copy-label">Parent Copy</div> --}}
            @include('challan.partials.challan_content', ['copyTitle' => 'Parent Copy'])
        </div>

        <!-- Bank Copy -->
        <div class="copy">
            {{-- <div class="copy-label">Bank Copy</div> --}}
            @include('challan.partials.challan_content', ['copyTitle' => 'Bank Copy'])
        </div>

        <!-- STMU Copy -->
        <div class="copy">
            {{-- <div class="copy-label">STMU Copy</div> --}}
            @include('challan.partials.challan_content', ['copyTitle' => 'STMU Copy'])
        </div>
    </div>
</body>
</html>
