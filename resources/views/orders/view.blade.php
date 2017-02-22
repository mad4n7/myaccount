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
    <form class="form form-horizontal" id="frmSend" method="POST" action="{{ url('orders') }}">
    {{ csrf_field() }}
    <input type="hidden" name="product_id" id="product_id" value="" />    
    <input type="hidden" name="product_periodicity" id="product_periodicity" value="" />    
    
    <div class="card">
        <div class="card-header">
            <p class="lead visible-print-inline">RECEIPT: Cat & Mouse <br /></p>
            <p class="hidden-print">Order ID: &nbsp; <strong>{{ $invoice->order_id }}</strong></p>
        </div>
        <div class="card-body">
          <!-- content -->
        <div class="section hidden-print">                        
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
                    <li role="step" <?php if($invoice->inv_status != 'p') { ?> class="active" <?php } ?> >
                        <a href="#step2" role="tab" id="step2-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-credit-card"></div>
                            <div class="heading">
                                <div class="title">Payment</div>
                                <div class="description">Billing Information.</div>
                            </div>
                        </a>
                    </li>

                    <li role="step" <?php if($invoice->inv_status == 'p') { ?> class="active" <?php } ?> >
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
    <?php if($invoice->inv_status == 'p') { ?>
          <div>              
          <p class="lead">Invoice number: {{ $invoice->invoice_id }}</p>
          <p class="lead">Paid on: <?php echo App\Http\Controllers\HelperController::funcDateMysqlToUSA($invoice->paid_date); ?></p>
          </div>
    <?php } ?>          
          
    <div class="row">
        <div class="col-xs-12">
          <div class="card">
                  
        <div class="card-body">
        
            
        <div class="section">
          <div class="section-title">Itens</div>
          <div class="section-body">
    
           <div class="form-group">              
              <div class="col-md-6">           
                  
                <div class="list-group">
                    <ul>
                    <?php foreach ($order_itens as $item){ ?>
                        <li>{{ $item->item_description }} - <?php echo \App\Http\Controllers\HelperController::funcConvertDecimalToCurrency($item->item_total); ?></li>
                    <?php } ?>
                    </ul>
                </div>
                
              </div>                           
            </div>              
          </div>
        </div>
            

        <!-- total -->
        <div class="section">
          <div class="section-title">Amount</div>
          <div class="section-body">

           <div class="form-group">
              <label class="col-md-3 col-xs-3 control-label">Total</label>
              <div class="col-md-6 col-xs-8">
                  <div class="lead text-success"><strong><?php echo \App\Http\Controllers\HelperController::funcConvertDecimalToCurrency($invoice->amount); ?> <span id="price_total"></span></strong></div>                
              </div>
            </div>
                         
                
              </div>                           
            </div>              
          </div>
              
        </div>        
            
        <?php if($invoice->inv_status != 'p') { ?>
        <div class="section">
          <div class="section-title">Select a Payment Type</div>
          <div class="section-body">

           <div class="form-group">
              <label class="col-md-3 control-label">PayPal</label>
              <div class="col-md-6">
                  <a href="{{url("invoice/paypal/checkout")}}/{{ $invoice->invoice_id }}" title="Pay with PayPal">                      
                    <img src="{{ asset('images/third_party/paypal_button.jpg') }}" title="Pay with PayPal" />
                  </a>
                <div id="domain_name_validate"></div>
              </div>
            </div>
            
          </div>
        </div>            
        <?php } ?>  
        <!-- end total -->
            
          
          </div>
            </div>
        </div>
    </div>          
          
          <!-- end content -->
            
            
        </div>
    </div>
</form>
</div>




        
@endsection
