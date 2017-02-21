@extends('layouts.dashboard')

@section('header_tags')
<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>


<script>
    $(function() {
        $("#term-length").hide();
        
        /* select the product */
        $( ".btn_product" ).click(function() {
            $("#term-length").show();
            
            <?php
            $i = 0;
            foreach($products as $product){
                echo '$("#pricing-table-item-'.$i.'" ).removeClass( "highlight" );';
                $i++;
            }
            ?>
            
            
            var current_number = $(this).attr('data-product-order');
            var product_id = $(this).attr('data-product-id');
            var product_price_month = $(this).attr('data-product-price-month');
            var product_price_year = $(this).attr('data-product-price-year');
            
            $("#pricing-table-item-" + current_number ).addClass( "highlight" );
            $("#product_id").val( product_id );
            
            $("#price_month").html( product_price_month );
            $("#term_length_month").val(product_price_month );
            $("#price_year").html( product_price_year );
            $("#term_length_year").val(product_price_year );
            sumItens();
            
        });  
        
        
        /* Form validation */
        $("#frmSend").validate({
                rules: {
                        domain_name: "required",
                        terms_conditions: "required"
                },
                messages: {
                        domain_name: "Please, type the website address(domain name)",
                        terms_conditions: "In order to continue you must agree to Cat&Mouse's Terms and Conditions."
                },
                errorPlacement: function (error, element) {
                    var name = $(element).attr("name");
                    error.appendTo($("#" + name + "_validate"));
                }
        });        
        
        

        
    });    
    
    function sumItens(){
        var total = 0;    
        
        var order = $( "input[type=radio][name=term_length]:checked" );
        var selected_price = order.val();
        var selected_period = order.attr('data-period');
        //console.log("Period:" + selected_period);
        $("#product_periodicity").val( selected_period );
                
        if ( $('input[name="ckb_add_migrate"]').is(':checked') ) {
            var item_total = $('input[name="ckb_add_migrate"]').val();
            total += parseFloat(item_total);        
        } 
        if ( $('input[name="ckb_add_ssl"]').is(':checked') ) {
            var item_total = $('input[name="ckb_add_ssl"]').val();
            total += parseFloat(item_total); 
        }                 
        
        total += parseFloat(selected_price);        
        console.log(total);

        $("#price_total").html( total );
        
         
    }
    
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
            <div class="section">                        
                        <div class="section-body">
                          <div class="step">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li role="step" class="active">
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
                    <div role="tabpanel" class="tab-pane active" id="step1">
                        <b>Step1</b> : Confirm your product and data.
                    </div>
                    <div role="tabpanel" class="tab-pane" id="step2">
                        <b>Step2</b> : Pay our invoice.
                    </div>
                    <div role="tabpanel" class="tab-pane" id="step3">
                        <b>Step3</b> : Just wait, we will activate your account.
                    </div>
                </div>
            </div>
            </div>
            </div>    
          
          <!-- pricing table -->
    <div class="row">
        <div class="col-xs-12">
          <div class="card">
            <div class="card-header">
              Hosting Plans
            </div>                  
        <div class="card-body no-padding">
            <div class="row no-gap">
                
            <?php
            $i = 0;
            foreach ($products as $product) { 
                ?>

              <div class="col-md-3 col-sm-6">
                <div id="pricing-table-item-{{ $i }}" class="pricing-table no-border-left">
                  <div class="pricing-heading">
                    <div class="title">{{ $product->prod_name }}</div>
                    <div class="price">
                      <div class="title">{{ $product->price_month }}<span class="sign">$</span></div>
                      <div class="subtitle">per month</div>
                    </div>

                  </div>
                  <div class="pricing-body">
                    <ul class="description">                      
                      <li><i class="icon ion-person-stalker"></i> <?php echo $product->prod_description; ?> <span class="small">Users</span></li>
                      <!-- <li><i class="icon ion-ios-chatboxes-outline"></i> or $ {{ $product->price_year }} <span class="small">/ year</span></li> -->
                    </ul>
                  </div>
                  <div class="pricing-footer">
                      <button type="button" class="btn btn-default btn-success btn_product" 
                              data-product-order="{{ $i }}" 
                              data-product-id="{{ $product->product_id }}"
                              data-product-price-month="{{ $product->price_month }}"
                              data-product-price-year="{{ $product->price_year }}">Select</button>
                  </div>
                </div>
              </div>
            <?php $i++; } ?>  
                
            </div>
          </div>
            </div>
        </div>
    </div>
    <!-- end  pricing table -->
    <br />
    <div id="term-length" class="section">
      <div class="section-title">Select term length</div>
      <div class="section-body">

        <div class="radio">
            <input type="radio" name="term_length" id="term_length_month" data-period="month" value="0" onchange="sumItens()" >
            <label for="term_length_month" class="lead">
                &nbsp; 1 month - $ <span id="price_month"></span>
            </label>
            
        </div>
        <div class="radio">
            <input type="radio" name="term_length" id="term_length_year"  data-period="year" value="0"  onchange="sumItens()" checked>
            <label for="term_length_year" class="lead">
                &nbsp; 12 months - $ <span id="price_year"></span> <span class="small text-danger">( Save 14% )</span>
            </label>            
        </div>
          <div id="price_selected" class="price_itens"></div>


      </div>
    </div>            
          
          
    <div class="row">
        <div class="col-xs-12">
          <div class="card">
                  
        <div class="card-body">
        
            
        <div class="section">
          <div class="section-title">Domain name</div>
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
                <div class="text-danger" id="domain_name_validate"></div>
              </div>
            </div>
                         
                
              </div>                           
            </div>              
          </div>
        </div>        
        <!-- end total -->
        <div class="section">
          <br />
          <div class="section-body">

              
           <div class="form-group">
              <label class="col-md-3 control-label">Do you agree with our Terms and Conditions?</label>
              <div class="col-md-9">           
               
                <div class="checkbox">
                    <input type="checkbox" id="terms_conditions" name="terms_conditions">
                    <label for="terms_conditions">
                        &nbsp; Yes, I agree with the Cat&Mouse <a href="#">Terms and Conditions</a>.
                    </label>
                    <br />
                    <div class="text-danger" id="terms_conditions_validate"></div>
                </div>                  
                
              </div>                           
            </div>              
          </div>
        </div>            
          
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
