@extends('layouts.dashboard')

@section('header_tags')
<script src="{{ asset('js/orders/cancel.js') }}"></script>
@endsection

@section('content')

<div class="col-sm-16 col-xs-12">
    <div class="card">
        <div class="card-header">
          Order Cancellation
        </div>
        <!-- alert message -->
        <div id="alert_message"></div>
        <!-- alert message -->
        <div class="card-body">
               <h2>Warning:</h2>
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 id="">Are you sure that you want to cancel your order?<a class="anchorjs-link" href="#oh-snap!-you-got-an-error!"><span class="anchorjs-icon"></span></a></h4>
            <p>All backups and information related to this order will be deleted. <br /> The information can't be recovered once they are deleted, the subscription will be cancelled once the period ends.</p>
            <p>
                <a type="button" class="btn btn-danger" onclick="deleteNow()" href="{{ url('/orders/cancel/'.$order->order_id.'/now') }}">Yes, I understand. Cancel now!</a>
                <a type="button" class="btn btn-link" href="{{ url('/home') }}">I don't want to delete my backups. Go back.</a>
            </p>
            </div>                                            
        </div>
    </div>
</div>    
@endsection
