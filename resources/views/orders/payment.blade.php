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
          Steps
        </div>
        <div class="card-body">
          <!-- content -->
          
          
    <div class="row">
        <div class="col-xs-12">
          <div class="card">
                  
        <div class="card-body">
        
            
        <div class="section">
          <div class="section-title">Payment</div>
          <div class="section-body">

           <div class="form-group">
              <label class="col-md-3 control-label">Domain name</label>
              <div class="col-md-6">
                <input type="text" id="domain_name" name="domain_name" class="form-control" placeholder="">
                <div id="domain_name_validate"></div>
              </div>
            </div>
              
           <div class="form-group">
              <label class="col-md-3 control-label">Additional services</label>
              <div class="col-md-9">           
                  
                <div class="checkbox">
                    <input type="checkbox" id="ckb_add_migrate" name="ckb_add_migrate" value="140" onchange="sumItens()">
                    <label for="ckb_add_migrate">
                        &nbsp; Migrate my website ( $ 140 up to 3 websites )
                    </label>
                </div>
                  
                <div class="checkbox">
                    <input type="checkbox" id="ckb_add_ssl" name="ckb_add_ssl" value="24"  onchange="sumItens()">
                    <label for="ckb_add_ssl">
                        &nbsp; SSL Certificate ( $ 24 / year )
                    </label>
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
                  <div class="lead text-success"><strong>$ <span id="price_total"></span></strong></div>
                <div id="domain_name_validate"></div>
              </div>
            </div>
                         
                
              </div>                           
            </div>              
          </div>
        </div>        
        <!-- end total -->
            
          
            <div class="form-footer">
                <div class="form-group">
                  <div class="col-md-2 pull-right">
                    <button type="submit" class="btn btn-primary btn-lg">Continue</button>
                  </div>
                </div>
            </div>
        
            
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
