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
          Payment
        </div>
        <div class="card-body">
          <!-- content -->
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
                            <div class="icon fa fa-server "></div>
                            <div class="heading">
                                <div class="title">Purchase Successfully</div>
                                <div class="description">Wait for us to setup your account</div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane" id="step1">
                        <b>Step1</b> : Confirm your product and data.
                    </div>
                    <div role="tabpanel" class="tab-pane active" id="step2">
                        <b>Step2</b> : Pay our invoice.
                    </div>
                    <div role="tabpanel" class="tab-pane" id="step3">
                        <b>Step3</b> : Just wait, we will activate your account.
                    </div>
                </div>
            </div>
            </div>
            </div>          
          
    <div class="row">
        <div class="col-xs-12">
          <div class="card">
                  
        <div class="card-body">
        
            
        <div class="section">
          <div class="section-title">Ordered Itens</div>
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
              <label class="col-md-3 control-label">Total</label>
              <div class="col-md-6">
                  <div class="lead text-success"><strong><?php echo \App\Http\Controllers\HelperController::funcConvertDecimalToCurrency($invoice->amount); ?> <span id="price_total"></span></strong></div>
                <div id="domain_name_validate"></div>
              </div>
            </div>
                         
                
              </div>                           
            </div>              
          </div>
              
        </div>        
            
            
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
