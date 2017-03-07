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
    <form class="form form-horizontal" id="frmSend" method="POST" action="#">
    {{ csrf_field() }}
    <input type="hidden" name="product_id" id="product_id" value="" />    
    <input type="hidden" name="product_periodicity" id="product_periodicity" value="" />    
    
    <div class="card">
        <div class="card-header">
            <p class="lead visible-print-inline">RECEIPT: Cat & Mouse <br /></p>
            <p class="hidden-print">Invoice Number: &nbsp; <strong class="lead">{{ $invoice->invoice_id }}</strong></p>
        </div>
        <div class="card-body">
          <!-- content -->        
    <?php if($invoice->inv_status == 'p') { ?>
          <div>              
          <p class="lead">Invoice number: {{ $invoice->invoice_id }}</p>
          <p class="lead">Paid on: <?php echo App\Http\Controllers\HelperController::funcDateMysqlToUSA($invoice->paid_date); ?></p>
          </div>
    <?php } else { ?>          
          <p class="lead">Status: <?php echo App\Http\Controllers\HelperController::returnPymtStatusByChar($invoice->inv_status) ?></p>
    <?php } ?>
          
    <div class="row">
        <div class="col-xs-12">
          <div class="card">
                  
        <div class="card-body">
        
            
        <div class="section">
          <div class="section-title">Description</div>
          <div class="section-body">
    
           <div class="form-group">              
              <div class="col-md-6">           
                  
                <div class="list-group">
                    <ul>
                        <li class="lead">{{ $invoice->inv_description }}</li>
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
          <div class="section-title">About the Status</div>
          <div class="section-body">

           <div class="form-group">
            <div class="col-md-8 col-xs-8 lead">
                <img src="{{ asset('images/payments/pending.png') }}" style="height: 64px;" /> We are processing your payment. This might take a few hours.
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
