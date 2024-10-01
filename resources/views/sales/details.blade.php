<div>
    <h5>Sales Details for the Last 7 Days</h5>

    @if($salesDetails->count() > 0)
        <div style="max-height: 300px; overflow-y: auto;"> <!-- Adding scroll to the table -->
            <table class="table table-bordered">
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
                            <td>{{ $sale->created_at }}</td>
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

    <!-- Close Modal Button -->
    <div class="text-end mt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div>
