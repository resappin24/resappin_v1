<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

@extends('layout.master')
@section('konten')
    <div class="row m-1">
        <div class="card col-md-12 mt-1">
            <div class="card-header bg-light">
                <div class="row">
                    <div class="col-md-6 text-start">
                        @if (!empty($selectedDate))
                            <h4>Data Transaksi - {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</h4>
                        @elseif (!empty($start) && !empty($end))
                            <h4>Data Transaksi ({{ \Carbon\Carbon::parse($start)->format('d F Y') }} - {{ \Carbon\Carbon::parse($end)->format('d F Y') }})</h4>
                        @else
                            <h4>Data Transaksi - Hari ini</h4>
                        @endif

                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transaksiModal">
                            <iconify-icon icon="mdi:add-box"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-md-12 text-start">
                    <form action="{{ url('/transaksi') }}" method="get">
                        <div class="input-group mb-3">
                            @if (!empty($selectedDate))
                                <input type="date" class="form-control" id="dateFilter" name="date" style="border-radius:20px;" value="{{ $selectedDate }}">
                            @else
                                <input type="date" class="form-control" id="dateFilter" name="date" style="border-radius:20px;" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                            @endif
                            <button class="btn btn-primary" type="submit" style="border-radius:20px; margin-right:5px; margin-left:5px;">Search by Transaction Date</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-12 text-start">
                    <form action="{{ url('/transaksi') }}" method="get">
                        <div class="input-group mb-3">
                            <label for="startDate" class="col-form-label" style="margin-right:5px; ">From</label>
                            <input type="date" class="form-control" name="start_date" style="border-radius:20px;" value="{{ $start }}">
                            
                            <label for="endDate" class="col-form-label" style="margin-right:5px; margin-left:5px; ">To</label>
                            <input type="date" class="form-control" name="end_date" style="border-radius:20px;" value="{{ $end }}">
                            
                            <button class="btn btn-primary" type="submit" style="border-radius:20px; margin-right:5px; margin-left:5px;">Search by Transaction Date Range</button>
                        </div>
                    </form>
                </div>
                <table id="example" class="table table-bordered table-striped text-center" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>QTY</th>
                            <th>Subtotal</th>
                            <th>Tanggal Transaksi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksi as $item)
                            <tr>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>Rp. @currency($item->subtotal)</td>
                                @if($item->updated_at == '')
                                    <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('d F Y') }}</td>
                                @else
                                    <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $item->updated_at)->format('d F Y') }}</td>
                                @endif
                                <td>
                                    <button class="btn btn-warning btn-edit" data-bs-toggle="modal"
                                        data-bs-target="#TransaksiEditModal" data-id="{{ $item->transaksiID }}" data-kerupuk="{{ $item->kerupukID }}"
                                        data-qty="{{ $item->qty }}" data-barang="{{ $item->nama_barang }}" 
                                        data-satuan="{{ $item->satuan }}" data-stok="{{ $item->stok }}" data-modal="{{ $item->modal }}">
                                        <iconify-icon icon="mingcute:edit-4-line"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

<div class="modal fade" id="transaksiModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/store_transaksi') }}" method="post" id="transaction">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama barang" class="col-form-label">Nama Barang</label>
                        <div class="dropdown-form">
                            <select name="kerupukID" id="kerupukSelect" class="form-control">
                                <option>Pilih Barang</option>
                                @foreach ($kerupuk as $item)
                                    @if ($item->main_stok > 0)
                                        <option value="{{ $item->kerupukID }}" data-harga="{{ $item->main_harga_jual }}" data-beli="{{ $item->harga_beli }}" data-stok="{{ $item->stok }}" data-barang="{{ $item->nama_barang }}">
                                            {{ $item->nama_barang }} - {{ $item->main_stok }}
                                        </option>
                                    @elseif($item->stok == 0)
                                        <option value="{{ $item->nama_barang }}" data-harga="{{ $item->harga_jual }}" data-stok="{{ $item->stok }}" class="text-danger" disabled>
                                            {{ $item->nama_barang }} - Habis
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" aria-describedby="basic-addon1" id="nama_barang" name="nama_barang" readonly>
                    <input type="hidden" class="form-control" aria-describedby="basic-addon1" id="modal" name="modal" readonly>
                    <div class="mb-3">
                        <label for="harga jual" class="col-form-label">Harga Jual</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp. </span>
                            <input type="number" class="form-control" aria-describedby="basic-addon1" id="harga_jual"
                                name="satuan" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="col-form-label">QTY</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" name="qty" id="qty" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subtotal" class="col-form-label">Subtotal</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp. </span>
                            <input type="number" class="form-control" aria-describedby="basic-addon1" id="subtotal"
                                name="subtotal" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="transaksiEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/update_transaksi') }}" method="post" id="transaction">
                @csrf @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <input type="hidden" name="kerupukID" class="form-control" aria-describedby="basic-addon1" id="edit-kerupukID" readonly>
                    <div class="mb-3">
                        <label for="nama barang" class="col-form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" aria-describedby="basic-addon1" id="edit-nama_barang" readonly>
                    </div>
                    <input type="number" name="modal" class="form-control edit-modal" aria-describedby="basic-addon1" readonly>
                    <div class="mb-3">
                        <label for="harga jual" class="col-form-label">Harga Jual</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp. </span>
                            <input type="number" name="satuan" class="form-control" aria-describedby="basic-addon1" id="edit-satuan" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="col-form-label">QTY</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control edit-qty" name="qty" id="qty" gt="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subtotal" class="col-form-label">Subtotal</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp. </span>
                            <input type="number" class="form-control" aria-describedby="basic-addon1" id="edit-subtotal" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            var kerupuk = $(this).data('kerupuk');
            var qty = $(this).data('qty');
            var barang = $(this).data('barang');
            var satuan = $(this).data('satuan');
            var stok = $(this).data('stok');
            var modal = $(this).data('modal');

            console.log(id, kerupuk, qty, barang, satuan, stok, modal);

            var subtotal = satuan * qty;

            $('#edit-id').val(id);
            $('.edit-qty').val(qty);
            $('#edit-satuan').val(satuan);
            $('#edit-subtotal').val(subtotal);
            $('#edit-kerupukID').val(kerupuk);
            $('#edit-nama_barang').val(barang);
            $('.edit-modal').val(modal);

            $('.edit-qty').attr('max', stok);

            console.log('ID Transaksi : ', $('#edit-id').val())
            console.log('ID Kerupuk : ', $('#edit-kerupukID').val())
            console.log('QTY : ', $('.edit-qty').val())
            console.log('Nama Kerupuk : ', $('#edit-nama_barang').val())
            console.log('Modal : ', $('.edit-modal').val())

            $('.edit-qty').on('input', function() {
            var qtyValue = $(this).val();
            var maxQty = parseInt($(this).attr('max'));

            qtyValue = parseInt(qtyValue);

            $(this).val(qtyValue);
            console.log('Qty:', qtyValue);
            updateSubtotal();
            });

            function updateSubtotal() {
                var newQty = parseInt($('.edit-qty').val()) || 0;

                newQty = Math.max(newQty, 0);

                var newSubtotal = satuan * newQty;
                console.log('New Subtotal:', newSubtotal);
                $('#edit-subtotal').val(newSubtotal);
            }

        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#transaksiModal').on('hidden.bs.modal', function () {
            $('#kerupukSelect').val('Pilih Barang');
            $('#nama_barang').val('');
            $('#harga_jual').val('');
            $('#qty').val('');
            $('#subtotal').val('');
            $('#modal').val('');
        });

        $('#kerupukSelect').change(function() {
            var selectedOption = $(this).find(':selected');
            var harga = parseFloat(selectedOption.data('harga'));
            var modal = parseFloat(selectedOption.data('beli'));
            var barang = selectedOption.data('barang');
            var stok = parseInt(selectedOption.data('stok'));

            console.log('stok:', stok);

            console.log('Harga:', harga);
            $('#harga_jual').val(harga);
            $('#nama_barang').val(barang);
            $('#modal').val(modal);

            $('#qty').attr('max', stok);

            updateSubtotal();
        });

        $('#qty').on('input', function() {
            var qtyValue = $(this).val();
            var maxQty = parseInt($(this).attr('max'));

            qtyValue = parseInt(qtyValue);

            $(this).val(qtyValue);
            console.log('Qty:', qtyValue);
            updateSubtotal();
        });

        function updateSubtotal() {
            var harga = parseFloat($('#harga_jual').val()) || 0;
            var qty = parseInt($('#qty').val()) || 0;

            qty = Math.max(qty, 0);

            var subtotal = harga * qty;
            console.log('Subtotal:', subtotal);

            $('#subtotal').val(subtotal);
        }
    });
</script>

<script>
$(document).ready(function() {
    var table = $('#example').DataTable({
        lengthChange: false,
        buttons: [{
            extend: 'excel',
            text: 'Excel',
            filename: function () {
                var currentDate = new Date();
                var day = ("0" + currentDate.getDate()).slice(-2);
                var month = ("0" + (currentDate.getMonth() + 1)).slice(-2);
                var year = currentDate.getFullYear();
                return 'PenjualanKerupuk_' + year + month + day;
            },
            customizeData: function (excelData) {
                for (var i = 0; i < excelData.body.length; i++) {
                    excelData.body[i][2] = excelData.body[i][2].replace('Rp. ', '');
                    excelData.body[i][2] = excelData.body[i][2].replace('.',''); //Ribuan
                    excelData.body[i][2] = excelData.body[i][2].replace('.',''); //Jutaan
                    excelData.body[i][2] = excelData.body[i][2].replace('.',''); //Miliaran
                }

                console.log('Modified Excel Data:', excelData);
            }
        }, 
        {
            extend: 'pdf',
            text: 'PDF',
            filename: function () {
                var currentDate = new Date();
                var day = ("0" + currentDate.getDate()).slice(-2);
                var month = ("0" + (currentDate.getMonth() + 1)).slice(-2);
                var year = currentDate.getFullYear();
                return 'PenjualanKerupuk_' + year + month + day;
            }
        },
            'colvis'
        ],
        "columns": [
            { "searchable": true },
            { "searchable": false },
            { "searchable": false },
            { "searchable": false },
            { "searchable": false }
        ]
    });

    table.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
});
</script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
