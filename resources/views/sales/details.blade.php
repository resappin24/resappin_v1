<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.11.5/sorting/datetime-moment.js"></script>

<div>
    <h5>Sales Details for the Last 7 Days</h5>

    @if($salesDetails->count() > 0)
        <div style="max-height: 250px; overflow-y: auto;">
            <table id="sales-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product Name</th>
                        <th>Quantity Sold</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesDetails as $sale)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y') }}</td>
                            <td>{{ $sale->nama_barang }}</td>
                            <td>{{ $sale->qty }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No sales in the last 7 days.</p>
    @endif
</div>

<script>
    $(document).ready(function() {
        $.fn.dataTable.moment('DD-MM-YYYY');
       var table = $('#sales-table').DataTable({
            "ordering": true,  
            "paging": true,   
            "searching": true, 
            "order": [[0, "desc"]],
            "columns": [
            { "orderable": true }, 
            { "orderable": true }, 
            { 
                "orderable": true,
                "type": "date"  
            } 
        ]
        });
        $('#sales-table_filter input').unbind().bind('keyup', function(e) {
            var searchTerm = this.value;
            var searchArray = searchTerm.split(" "); // Split by space for two keywords
            var regexSearch = searchArray.join("|"); // Create regex for "OR" between keywords
            table.search(regexSearch, true, false).draw(); // Enable regex search
        });
    });
</script>
