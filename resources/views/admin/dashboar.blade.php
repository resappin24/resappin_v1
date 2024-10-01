@extends('layout.master')

@section('konten')
    <div class="row m-1">
        <div class="card col-md-7 m-1">
            <div class="card-header text-center bg-light">
                <h3>Sales per Week</h3>
            </div>
            <div class="card-body">
                <canvas id="myChart"></canvas>
                <div class="text-end">
                  <button type="button" class="btn btn-sm btn-detail" data-bs-toggle="modal" data-bs-target="#details-modal" data-id="1">
                      Show Detail
                  </button>
                </div>
       
            </div>
            
        </div>
        <div class="card col-md-4 m-1" style="width: 39%">
            <div class="card-header text-center bg-light">
                Day Profit
            </div>
            <div class="card-body">
                <canvas id="myPieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row m-1">
        <div class="card col-md-4 m-1" style="height: 70%">
            <div class="card-header text-center bg-light">
                <h3>Sales per Week</h3>
            </div>
            <div class="card-body">
                <canvas id="myLineChart"></canvas>
             
       
            </div>
            
        </div>
</div>
</div>
@endsection

<!-- Modal Detail-->
<div id="details-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="details-modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content detail-dashboard">
      <div class="modal-header">
        <h4 class="modal-title">Details</h4>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('desain') }}/js/bar.js"></script>
<script src="{{ asset('desain') }}/js/pie.js"></script>
<script src="{{ asset('desain') }}/js/line.js"></script>

<script>
  $(document).ready(function () {
    // Saat tombol Show Detail ditekan
    $('.btn-detail').on('click', function () {
        var id = $(this).data('id'); // Ambil id dari tombol
        var modal = $('#details-modal');

        // Bersihkan konten modal
        modal.find('.modal-body').html('');

        // Lakukan AJAX request ke route yang sesuai
        $.ajax({
            url: '/sales/details/', // URL untuk mengambil detail data
            method: 'GET',
            success: function (response) {
                // Masukkan response ke dalam modal-body
                modal.find('.modal-body').html(response);
            },
            error: function (xhr) {
                console.log(xhr.responseText); // Menampilkan error di konsol jika terjadi kesalahan
                modal.find('.modal-body').html('<p class="text-danger">Error retrieving details</p>');
            }
        });
    });
});

</script>