@extends('layouts.dashboard')

@section('content')


<div class="col-sm-16 col-xs-12">
    <div class="card">
        <div class="card-header">
          Payment Status
        </div>
        <div class="card-body">
            
<?php if($approved == 1){ ?>
<div class="section">                        
        <div class="section-body">
            <div class="step">
        <ul class="nav nav-tabs nav-justified" role="tablist">
            <li role="step">
                <a href="#step1" role="tab" id="step1-tab" data-toggle="tab" aria-controls="profile">
                    <div class="icon fa fa-check"></div>
                    <div class="heading">
                        <div class="title">Confirm Orders</div>
                        <div class="description">Confirmation your purchases</div>
                    </div>
                </a>
            </li>                    
            <li role="step">
                <a href="#step2" role="tab" id="step2-tab" data-toggle="tab" aria-controls="profile">
                    <div class="icon fa fa-credit-card"></div>
                    <div class="heading">
                        <div class="title">Payment</div>
                        <div class="description">Billing Information.</div>
                    </div>
                </a>
            </li>

            <li role="step" class="active">
                <a href="#step3" role="tab" id="step3-tab" data-toggle="tab" aria-controls="profile">
                    <div class="icon fa fa-server "></div>
                    <div class="heading">
                        <div class="title">Purchase Successfully</div>
                        <div class="description">Wait for us to setup your account</div>
                    </div>
                </a>
            </li>
        </ul>

    </div>
    </div>
</div>             
<?php } ?>            
            
            
            <h4>{{$message}}</h4>
        </div>
    </div>
</div>




        
@endsection
