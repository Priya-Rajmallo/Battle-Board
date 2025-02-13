<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Invoice</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            /* margin: 0; */
            /* padding: 20px; */
            color: #333;
        }

        .invoice-container {
            /* width: 100%; */
            /* max-width: 800px; */
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        }

        .header,
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo {
            width: 160px;
            height: 160px;
            /* background: #ddd; */
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 24px;
            /* border: 1px solid #ddd; */
        }

        .title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            background: #d1e7ff;
            padding: 10px;
        }

        .details,
        .terms {
            width: 100%;
            margin-bottom: 20px;
        }

        .details table,
        .item-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details td,
        .item-table th,
        .item-table td,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .item-table th {
            background-color: #d1e7ff;
            font-weight: bold;
        }

        .summary-table td {
            padding: 10px;
        }

        .summary-table .label {
            text-align: right;
        }

        .summary-table .total {
            background: #d1e7ff;
            font-weight: bold;
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 20px;
        }

        button {
            cursor: pointer;
            font-weight: 700;
            transition: all 0.2s;
            padding: 10px 20px;
            border-radius: 100px;
            background: #cfef00;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            font-size: 15px;
            margin: 10px;
        }

        button:hover {
            background: #c4e201;
        }

        button>svg {
            width: 34px;
            margin-left: 10px;
            transition: transform 0.3s ease-in-out;
        }

        button:hover svg {
            transform: translateX(5px);
        }

        button:active {
            transform: scale(0.95);
        }

        @media print {
            .invoice-container {
                border: 0px solid #ddd;
                padding: 0px;
            }

            .button-container {
                display: none;
            }
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="invoice-container">
        <div class="header">
            <div>
                <p><strong>TESTUDINIDAE GLOBAL(OPC) PRIVATE LIMITED</strong><br>
                    Address: C-85, BHIMPUR, PALLAS PALLI UNIT-1, PS-AIRFIELD<br>
                    BHUBANESWAR Khordha, Odisha, India<br>
                    Phone No.: +91 9090737079<br>
                    Email: support@testudinidaeglobal.com<br>
                    GSTIN: 21AAJCT4498B1ZE<br>
                    State: Odisha<br>
                    Country: India
                </p>
            </div>
            <div class="logo">
                <img src="{{ asset('assets/bg-logo.png') }}" width="150px" alt="Logo"></br>
            </div>
        </div>

        <div class="title">Deposit Invoice</div>

        <div class="details">
            <table>
                <tr>
                    <td>
                        <strong>Bill To:</strong><br>Name:
                        {{ $data->user->name }}<br>User ID:
                        {{ $data->user->str_user_id }}<br>
                        Address: {{ $data->user->address . ', ' . $data->user->country }}<br>
                        {{ $data->user->phone }}
                    </td>
                    </td>
                    {{-- <td><strong>Contact No.:</strong>{{ $data->user->phone }}</td> --}}
                    <td><strong>Invoice No.:</strong> {{ $data->transaction->transaction_id }}<br>
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}
                    </td>
                    {{-- <td>
                        <strong>Shipping To:</strong><br>Name:
                        {{ $data->user->name }}<br>User ID:
                        {{ $data->user->str_user_id }}<br>
                        Address: {{ $data->user->address . ', ' . $data->user->country }}
                    </td> --}}
                </tr>
                {{-- <tr>
                    <td><strong>Contact No.:</strong>{{ $data->user->phone }}</td>
                    <td><strong>Invoice No.:</strong> {{ $data->transaction->transaction_id }}<br>
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}
                    </td>
                </tr> --}}
                @if ($data->user->address)
                    <tr>
                        <td></td>
                        <td><strong>State:</strong>{{ $data->user->address }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <table class="item-table">
            <tr>
                <th>#</th>
                <th>Item Name</th>
                {{-- <th>Quantity</th> --}}
                <th>Unit</th>
                <th>Price/Unit</th>
                <th>GST</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Battleboards Coin</td>
                {{-- <td>1</td> --}}
                <td>1</td>
                <td class="text-right">{{ $data->remaining_amount }}</td>
                <td>{{ $data->percentage }}%</td>
                <td class="text-right">{{ $data->total_amount }}</td>
            </tr>
            <!-- Add more rows as necessary -->
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td>1</td>
                <td colspan="2"></td>
                <td class="text-right">{{ $data->total_amount }}</td>
            </tr>
        </table>

        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <div style="width: 60%;">
                <p><strong>Amount in words:</strong><br>{{ ucfirst($AmountInWords) }}</p>
                <p><strong>Terms & Conditions</strong><br>[Terms & Conditions here]</p>
            </div>
            <table class="summary-table" style="width: 35%;">
                <tr>
                    <td class="label">Sub Total:</td>
                    <td class="text-right">{{ $data->remaining_amount }}</td>
                </tr>
                @if ($data->is_igst === 'yes')
                    <tr>
                        <td class="label">IGST ({{ $data->percentage }}%):</td>
                        <td class="text-right">{{ $data->gst_amount }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="label">SGST ({{ $data->percentage / 2 }}%):</td>
                        <td class="text-right">{{ $data->gst_amount / 2 }}</td>
                    </tr>
                    <tr>
                        <td class="label">CGST ({{ $data->percentage / 2 }}%):</td>
                        <td class="text-right">{{ $data->gst_amount / 2 }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="label">GST Total:</td>
                    <td class="text-right">{{ $data->gst_amount }}</td>
                </tr>
                <tr>
                    <td class="label total">Total:</td>
                    <td class="text-right">{{ $data->total_amount }}</td>
                </tr>
            </table>
        </div>

        <div class="footer" style="text-align: right; margin-top: 40px;">
            <!-- <p>Company seal and Sign</p> -->
        </div>
    </div>
    <div class="button-container">
    <!-- Close Button -->
    <button onclick="window.close();"
        style="display: flex; align-items: center; gap: 8px; background: rgb(241, 51, 51); color: white; border: none; padding: 8px 12px; border-radius: 5px;">
        <span>Close</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" height="24" width="24">
            <path d="M18.3 5.7a1 1 0 00-1.4-1.4L12 9.6 7.1 4.7a1 1 0 00-1.4 1.4L10.6 12l-4.9 4.9a1 1 0 101.4 1.4L12 14.4l4.9 4.9a1 1 0 001.4-1.4L13.4 12l4.9-4.9z"></path>
        </svg>
    </button>

    <!-- Print Button -->
    <button onclick="window.print();"
        style="display: flex; align-items: center; gap: 8px; background: rgb(23, 133, 23); color: white; border: none; padding: 8px 12px; border-radius: 5px;">
        <span>Print</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" height="24" width="24">
            <path d="M19 8h-1V3H6v5H5a3 3 0 00-3 3v5h5v4h10v-4h5v-5a3 3 0 00-3-3zM8 5h8v3H8V5zm8 14H8v-4h8v4zm3-6H5v-3a1 1 0 011-1h12a1 1 0 011 1v3z"></path>
        </svg>
    </button>

    <!-- Download Button -->
    <button id="downloadButton"
        style="display: flex; align-items: center; gap: 8px; background: rgb(0, 123, 255); color: white; border: none; padding: 8px 12px; border-radius: 5px;">
        <span>Download</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" height="24" width="24">
            <path d="M12 16l4-5h-3V4h-2v7H8l4 5zM5 18h14v2H5v-2z"></path>
        </svg>
    </button>
</div>
<script>
    document.getElementById('downloadButton').addEventListener('click', function() {
        // Define the element to download (entire page)
        const element = document.body; 

        // Use html2pdf to convert and download
        html2pdf().from(element).save('page.pdf');
    });
</script>


</body>

</html>
