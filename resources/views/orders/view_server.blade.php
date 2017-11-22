@extends('layouts.dashboard')

@section('header_tags')
<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>


<script>
    $(function() {      
        
    });    
    
    
</script>
@endsection

@section('content')


    
        <div class="col-sm-16 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h2  id="server_details"></h2><i class="fa fa-laptop fa-2x" aria-hidden="true"></i> &nbsp; Server Details
                </div>
                <div class="card-body lead">
                    <p>Control Panel: <a href="https://arthursilva.com/cpanel" target="_new">arthursilva.com/cpanel</a></p>
                    <p>domain name: <strong>{{ $order->domain_name }}</strong></p>
                    <?php if( !empty($order->domain_user) && !empty($order->domain_password) ) { ?>
                        <p>user: <strong>{{ $order->domain_user }}</strong></p>
                        <p>password: <strong>{{ $order->domain_password }}</strong></p>
                    <?php } else { ?>
                    <span class="label label-warning">If you ordered a hosting account, your user name and password will be available soon.</span>
                    <?php } ?>
                </div>
            </div>
        </div>

    

        
@endsection
