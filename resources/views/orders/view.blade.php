@extends('layouts.dashboard')

@section('header_tags')
<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>


<script>
    $(function() {      
        
        <?php
        $order->periodicity;
        ?>
        

        
    });    
  
    
    
</script>

    
@endsection

@section('content')


<div class="col-sm-16 col-xs-12">
    <div class="form form-horizontal" id="frmSend">
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
                                <div class="title">Select your product</div>
                                <div class="description">Select the best option for your business</div>
                            </div>
                        </a>
                    </li>                    
                    <li role="step" <?php if($invoice->inv_status != 'p') { ?> class="active" <?php } ?> >
                        <a href="#step2" role="tab" id="step2-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-credit-card"></div>
                            <div class="heading">
                                <div class="title">Payment</div>
                                <div class="description">Billing Information</div>
                            </div>
                        </a>
                    </li>

                    <li role="step" <?php if($invoice->inv_status == 'p') { ?> class="active" <?php } ?> >
                        <a href="#step3" role="tab" id="step3-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-server "></div>
                            <div class="heading">
                                <div class="title">Purchase Successful</div>
                                <div class="description">We'll have you up and running within 24 hours</div>
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
                    
                        <li>{{ $invoice->inv_description }} - <?php echo \App\Http\Controllers\HelperController::funcConvertDecimalToCurrency($invoice->amount); ?></li>
                    
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
          <div class="section-title">Checkout</div>
          <div class="section-body">
 
           <!-- Payment methods -->     
           <div class="form-group" id="small_m">
              
              <div class="col-md-8 col-xs-8 lead">
                    <img src="{{ asset('images/payments/pending.png') }}" style="height: 64px;" /> We are processing your payment. This might take a few hours.                  
              </div>
            </div>
           
           <!-- End Payment methods -->
            
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
   
        
@endsection
