<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">

@extends('layout.master')
@section('konten')

<style>
    body {
    /* background: rgb(99, 39, 120) */
}

.form-control:focus {
    box-shadow: none;
    border-color: #BA68C8
}

.profile-button {
    background: rgb(99, 39, 120);
    box-shadow: none;
    border: none
}

.profile-button:hover {
    background: #682773
}

.profile-button:focus {
    background: #682773;
    box-shadow: none
}

.profile-button:active {
    background: #682773;
    box-shadow: none
}

.back:hover {
    color: #682773;
    cursor: pointer
}

.labels {
    font-size: 11px
}

.container-profile {
    width : 800px;
    margin-left : 20px;
    margin-top: -80px;
    height: auto;
    /* margin-bottom: 20px; */
}

.add-experience:hover {
    background: #BA68C8;
    color: #fff;
    cursor: pointer;
    border: solid 1px #BA68C8
}

.height {
    height : 400px;
}
</style>

<div class="container-profile rounded bg-white mt-5 mb-5">
    <div class="row height">
        <div class="col-md-4 border-right height">
            <div class="d-flex flex-column align-items-center text-center p-3 py-3"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
            <!-- <span class="font-weight-bold">ZENDY</span><span class="text-black-50">ZENDY@INSPIROTECHS.com</span><span> </span> -->
        </div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-3 height">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="text-right"><b>Profile</b></h3>
                </div>
              
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">Name</label></div>
                    <!-- <div class="col-md-6"><label class="labels">username</label><input type="text" class="form-control" value="" placeholder="surname"></div> -->
                </div>
                <div class="row mt-2">
                <div class="col-md-12"><h4>{{ $profile->name }}</h4></div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">Username</label></div>
                    <!-- <div class="col-md-6"><label class="labels">username</label><input type="text" class="form-control" value="" placeholder="surname"></div> -->
                </div>
                <div class="row mt-2">
                <div class="col-md-12"><h4>{{ $profile->username }}</h4></div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6"><label class="labels">Email</label></div>
                    <!-- <div class="col-md-6"><label class="labels">username</label><input type="text" class="form-control" value="" placeholder="surname"></div> -->
                </div>
                <div class="row mt-2">
                <div class="col-md-12"><h4>{{ $profile->email }}</h4></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text" class="form-control" placeholder="enter phone number" value=""></div>
                    <!-- <div class="col-md-12"><label class="labels">Email</label><input type="text" class="form-control" placeholder="enter address line 1" value=""></div> -->
                    <!-- <div class="col-md-12"><label class="labels">Address Line 2</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div> -->
                    <!-- <div class="col-md-12"><label class="labels">Postcode</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div> -->
                    <!-- <div class="col-md-12"><label class="labels">State</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div> -->
                    <!-- <div class="col-md-12"><label class="labels">Area</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div> -->
                    <!-- <div class="col-md-12"><label class="labels">Email ID</label><input type="text" class="form-control" placeholder="enter email id" value=""></div> -->
                    <!-- <div class="col-md-12"><label class="labels">Education</label><input type="text" class="form-control" placeholder="education" value=""></div> -->
                </div>
                <!-- <div class="row mt-3">
                    <div class="col-md-6"><label class="labels">Country</label><input type="text" class="form-control" placeholder="country" value=""></div>
                    <div class="col-md-6"><label class="labels">State/Region</label><input type="text" class="form-control" value="" placeholder="state"></div>
                </div> -->
                <div class="mt-3 text-center"><button class="btn btn-primary profile-button" type="button" disabled>Save Profile</button></div>
            </div>
        </div>
    
        <!-- <div class="col-md-3 height">
            <div class="p-3 py-3">
                <div class="d-flex justify-content-between align-items-center experience"><span>Edit Experience</span><span class="border px-3 p-1 add-experience"><i class="fa fa-plus"></i>&nbsp;Experience</span></div><br>
                <div class="col-md-12"><label class="labels">Experience in Designing</label><input type="text" class="form-control" placeholder="experience" value=""></div> <br>
                <div class="col-md-12"><label class="labels">Additional Details</label><input type="text" class="form-control" placeholder="additional details" value=""></div>
            </div>
        </div> -->
    </div>
</div>
</div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>