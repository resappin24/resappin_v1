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
    <div class="modal-dialog custom-modal-size">
      <div class="modal-content" style="width: 800px;">
        <div class="modal-header">
          <h4 class="modal-title">Details</h4>
          {{-- <button type="button" class="btn-close" data-dismiss="modal">
            &times;
          </button> --}}
        </div>
        <div class="modal-body"></div>
        <div class="text-end mt-3 p-4">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
   
  </div>
  

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('desain') }}/js/bar.js"></script>
<script src="{{ asset('desain') }}/js/pie.js"></script>
<script src="{{ asset('desain') }}/js/line.js"></script>

<style>
    .custom-modal-size {
    max-width: 850px !important;
    max-height: 800px;
}

</style>

<script>
  $(document).ready(function () {
    $('.btn-detail').on('click', function () {
        var id = $(this).data('id'); 
        var modal = $('#details-modal');

        modal.find('.modal-body').html('');

        $.ajax({
            url: '/sales/details/', 
            method: 'GET',
            success: function (response) {
                modal.find('.modal-body').html(response);
            },
            error: function (xhr) {
                console.log(xhr.responseText); 
                modal.find('.modal-body').html('<p class="text-danger">No Sales for Last 7 Days</p>');
            }
        });
    });
});

</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.11.5/sorting/datetime-moment.js"></script>