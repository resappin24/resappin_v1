<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

@extends('layout.master')
@section('konten')

<style>
        .bg-header {
            width: 100%;
            height: 100%;
            background-color: #FF6600;
        }
        .bg-content {
            width: 100%;
            height: auto;
            background-color: #ffac8e ;
        }
        .bg-table {
            width: 100%;
            height: 100%;
            background-color: white;
        }
        .my-footer {
            text-align: center;
            padding: 10;
        }
        .btn-primary {
            width: 105px;
        }

        .title-menu {
            color:  #FFD700;
        }

        .btn-close2{
            width: 105px;
            background-color: grey;
            color: white;
            height:35px;
        }

        .btn-close2:hover{
            background-color: #A9A9A9;
        }

</style>
    <div class="row m-1">
        <div class="card col-md-12 mt-1 bg-content">
            <div class="card-header bg-content">
                <div class="row  bg-header">
                    <div class="col-md-6 text-start mt-4 mb-3">
                        <h3 class="title-menu"><b>KATEGORI BARANG</b></h3>
                    </div>
                    <div class="col-md-6 text-end  mt-4">
                        <button class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#vendorModal">
                            <iconify-icon icon="subway:add"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
            <div class="bg-content">
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped text-center bg-table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Created date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategori as $item)
                            <tr>
                                <td>{{ $item->kategori }}</td>
                                <td>{{$item->created_date}}</td>
                                <td>
                                    <button class="btn btn-warning btn-edit" data-bs-toggle="modal"
                                        data-bs-target="#editModal" data-id="{{ $item->id }}"
                                        data-nama="{{ $item->kategori }}" 
                                        disabled="true">
                                        <iconify-icon icon="mingcute:edit-4-line"></iconify-icon>
                                    </button>

                                    <button data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" class="btn btn-danger btn-delete" 
                                        data-id="{{ $item->id }}" data-nama="{{ $item->kategori }}">
                                        <iconify-icon icon="bi:trash-fill"></iconify-icon>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var hargaBeli = $(this).data('harga-beli');
            var hargaJual = $(this).data('harga-jual');
            var stok = $(this).data('stok');
            var gambar = $(this).data('gambar');

            var keuntungan = (hargaJual - hargaBeli) / hargaBeli * 100

            console.log(id, nama, hargaBeli, hargaJual, stok, gambar, keuntungan);

            $('.harga_beli').val('');
            $('#update-keuntungan').val('');

            $('.harga_beli').on('input', function() {
                var beli = $(this).val()
                jual()
            })

            $('#update-keuntungan').on('input', function() {
                var persen = $(this).val()

                persen = parseInt(persen);

                $(this).val(persen);
                console.log(persen)
                jual()
            })

            function jual() {
                var beli = parseFloat($('.harga_beli').val()) || 0;
                var persen = parseFloat($('#update-keuntungan').val()) || 0;
                beli = Math.max(beli, 0);
                persen = Math.max(persen, 0);

                var jual = beli * persen / 100 + beli
                $(this).val(jual);
                $('#edit-harga-jual').val(jual);
            }

            $('#edit-stok').on('input', function() {
                var stokValue = $(this).val();

                // Ensure stok is between 0 and maxstok (stock)
                stokValue = parseInt(stokValue);

                if (isNaN(stokValue) || stokValue < 0) {
                    stokValue = 0;
                }

                $(this).val(stokValue);
                console.log('stokValue:', stokValue);
            });

            stok = Math.max(stok, 0);

            $('#edit-id').val(id);
            $('#edit-nama-barang').val(nama);
            $('#update-keuntungan').val(keuntungan);
            $('#edit-harga-beli').val(hargaBeli);
            $('#edit-harga-jual').val(hargaJual);
            $('#edit-stok').val(stok);

            $('#edit-gambar-preview').attr('src', '{{ asset('gambar_barang/') }}' + '/' + gambar);

            if (gambar !== 'gambar-default.png') {
                $('#edit-gambar-container').hide();
                $('#edit-gambar-ada').show();
                $('#edit-gambar-update').show();
            } else {
                $('#edit-gambar-container').show();
                $('#edit-gambar-ada').hide();
                $('#edit-gambar-update').hide();
            }

            $(document).on('click', '.edit-gambar-update', function(event) {
                event.preventDefault();

                $('#edit-gambar-container').show();
                $('#edit-gambar-ada').hide();
                $('#edit-gambar-update').hide(); 
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-add', function() {

            $('#stok').on('input', function() {
                var stokValue = $(this).val();

                stokValue = parseInt(stokValue);

                if (isNaN(stokValue) || stokValue < 0) {
                    stokValue = 0;
                }

                $(this).val(stokValue);
                console.log('stokValue:', stokValue);
            });

            $('#harga_beli').val('');
            $('#keuntungan').val('');

            $('#harga_beli').on('input', function() {
                var beli = $(this).val()

                beli = parseInt(beli);

                $(this).val(beli);
                console.log(beli)
                jual()
            })

            $('#keuntungan').on('input', function() {
                var persen = $(this).val()

                persen = parseInt(persen);

                $(this).val(persen);
                console.log(persen)
                jual()
            })

            function jual() {
                var beli = parseFloat($('#harga_beli').val()) || 0;
                var persen = parseFloat($('#keuntungan').val()) || 0;
                beli = Math.max(beli, 0);
                persen = Math.max(persen, 0);

                var jual = beli * persen / 100 + beli
                

                console.log('harga:', jual)
                $('#harga_jual').val(jual);
            }

            stok = Math.max(stok, 0);
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-delete', function() {
            var nama = $(this).data('nama');
            var id = $(this).data('id');

            $('#deleteItemName').text(nama);
            $('#confirmDelete').data('id', id);

            $('#deleteModal').modal('show');
        });

        $(document).on('click', '#confirmDelete', function() {
            var id = $(this).data('id');
            
            window.location.href = "{{ url('/kategori/delete') }}/" + id;
        });
    });
</script>

{{-- Modal Delete kategori --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <p>Apakah kamu yakin ingin menghapus <span id="deleteItemName"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
        </div>
    </div>
</div>

{{-- Modal Add Kategori --}}
<div class="modal fade" id="vendorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/store_kategori') }}" method="post" enctype="multipart/form-data">
            @csrf
                <div class="modal-body">
                <div class="mb-3">
                 <label class="col-form-label">Kategori(</label><span class="required">*</span>)
                    <div class="input-group {{ $errors->has('kategori') ? '' : 'mb-3' }}">
                         
                        <input type="text" name="kategori" class="form-control custom-input" placeholder="" aria-label="" aria-describedby="basic-addon1" value="{{ old('name') }}">
                    </div>
                    @if ($errors->has('kategori'))
                    <div class="text-danger mb-1" id="errorMessage">{{ $errors->first('kategori') }}</div>
                    @endif
                    </div>
                </div>
                <div class="my-footer mb-4">
                   <button type="button" class="btn btn-close2" data-bs-dismiss="modal">Close</button> 
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Vendor  --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/update_kerupuk') }}" id="edit-form" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-3">
                        <label class="col-form-label">Nama Barang :</label>
                        <input type="text" class="form-control" id="edit-nama-barang" name="nama_barang"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">Harga Beli:</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp. </span>
                            <input type="number" class="form-control harga_beli" aria-describedby="basic-addon1"
                                id="edit-harga-beli" name="harga_beli">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">Keuntungan</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" aria-describedby="basic-addon1"
                            id="update-keuntungan">
                            <span class="input-group-text" id="basic-addon1">%</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">Harga Jual:</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp. </span>
                            <input type="number" class="form-control harga_jual" aria-describedby="basic-addon1"
                                id="edit-harga-jual" name="harga_jual" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="col-form-label">Stok:</label>
                        <input type="number" class="form-control" id="edit-stok" name="stok">
                    </div>

                    <div class="mb-3" id="edit-gambar-update">
                        <button class="btn btn-primary edit-gambar-update">Ubah Gambar</button>
                    </div>

                    <div class="mb-3" id="edit-gambar-container">
                        <label class="col-form-label">Gambar Barang:</label>
                        <input class="form-control" type="file" id="edit-gambar" name="gambar_barang">
                    </div>

                    <div class="mb-3" id="edit-gambar-ada">
                        <label class="col-form-label">Gambar Barang:</label>
                        <img id="edit-gambar-preview" width="100%">
                    </div>
                </div>
                <input type="hidden" value="Update Master Barang" name="activity">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "columns": [
                { "searchable": false },
                { "searchable": true },
                { "searchable": false }
            ]
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
