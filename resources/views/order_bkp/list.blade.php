@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                 
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li role="step">
                            <a href="#step1" id="step1-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">
                                <div class="icon fa fa-shopping-cart"></div>
                                <div class="heading">
                                    <div class="title">Shipping</div>
                                    <div class="description">Enter your address</div>
                                </div>
                            </a>
                        </li>
                        <li role="step" class="active">
                            <a href="#step2" role="tab" id="step2-tab" data-toggle="tab" aria-controls="profile">
                                <div class="icon fa fa-credit-card"></div>
                                <div class="heading">
                                    <div class="title">Payment</div>
                                    <div class="description">Billing Information.</div>
                                </div>
                            </a>
                        </li>
                        <li role="step">
                            <a href="#step3" role="tab" id="step3-tab" data-toggle="tab" aria-controls="profile">
                                <div class="icon fa fa-check"></div>
                                <div class="heading">
                                    <div class="title">Confirm Orders</div>
                                    <div class="description">Confirmation your purchases</div>
                                </div>
                            </a>
                        </li>
                        <li role="step">
                            <a href="#step4" role="tab" id="step4-tab" data-toggle="tab" aria-controls="profile">
                                <div class="icon fa fa-truck "></div>
                                <div class="heading">
                                    <div class="title">Purchase Successfully</div>
                                    <div class="description">Wait for us shipping</div>
                                </div>
                            </a>
                        </li>
                    </ul>                    
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
