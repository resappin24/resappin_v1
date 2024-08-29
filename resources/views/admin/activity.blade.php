<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">


@extends('layout.master')
@section('konten')
<div class="row m-1">
    <div class="card col-md-12 mt-1">
        <div class="card-header bg-light">
            <h3>Activity Logs</h3>
        </div>
        <div class="card-body">
            <table id="example" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Item</th>
                        <th>Activity</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activity as $item)
                    <tr>
                        <td>{{ $item->name_user }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->activity }}</td>
                        <!-- <td>{{ DateTime::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('d-m-Y H:i:s') }}</td> -->
                         <td>{{ $item->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>