@extends('layouts.dashboard')

@section('header_tags')
<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>


<script>
    $(function() {               
        
        $("#term-length").hide();
        
        var urlParams = new URLSearchParams(window.location.search);
        var product = urlParams.get('product');
        if(product > 0){
            changeCycle();
        }
        
        
        /* Form validation */
        $("#frmSend").validate({
                rules: {
                    
                        <?php if (!Auth::check()) {  ?>  
                        name: "required",
                        email: "required",
                        password: "required",
                        password_confirmation: {
                          equalTo: "#password"
                        },
                        <?php } ?>
                    
                        domain_name: "required",
                        terms_conditions: "required",
                        product_id: "required",
                        hosting_plan: "required",
                        billing_cycle: "required"
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
        
        /* Shows the new prices according to the Billing Cycle */
        $( "#billing_cycle" ).change(function() {            
            
            var type = $("#billing_cycle").val();
            var product_id = $("#hosting_plan").val();
            var price = "";
            var plan_renews = "";
            
            
            $.getJSON ('<?php echo url('json/invoices/get_renews_on_by_billing_cycle'); ?>?type=' + type , function (str_date){                                                

                $.getJSON ('<?php echo url('json/invoices/get_prices_by_product'); ?>?id=' + product_id , function (data){                                                

                    if(type == 'monthly'){
                        price = data.monthly_price;
                        plan_renews = "( Renews Monthly )";
                    }
                    else {
                        price = data.anually_price;
                        plan_renews = "( Renews Anually )";
                    }               
                    /* When it will renew) */
                    $("#plan_renews").replaceWith('<div id="plan_renews"  class="text-muted">Plan renews ' + str_date + ' at $ ' + price + '</div>');                                           
                    $('#db_product_price').val(price); //defines produt price
                    $('#product_id').val(product_id);   //defines product id
                    $('#product_periodicity').val(type); // periodicity
                    
                    /* Review section */        
                    $("#review_product").replaceWith('<div id="review_product" data-price="' + price + '" class="lead">' 
                            + data.product_name  + ' <strong class="pull-right"> $ ' + price +' </strong> ' + plan_renews + ' </div>');                    
                    sumItens();
                });
            }); 
        });    
        
        $( "#ckb_add_migrate" ).change(function() {
            /* div review migration */      
            sumItens(); // sum items
            var checked_input = $(this).is(':checked');
            if(!checked_input){
                $("#review_extra_migration").replaceWith('<div id="review_extra_migration" class="lead">' 
                                    + '</div>');                
            }
            else {
            $("#review_extra_migration").replaceWith('<div id="review_extra_migration" class="lead">' 
                    + 'Migrate my Website <strong class="pull-right"> $ 60 </strong> </div>');                            
            }
        });
        
        $( "#ckb_add_ssl" ).change(function() {
            sumItens(); // sum items
            var checked_input = $(this).is(':checked');
            /* div review migration */    
            if(!checked_input){
                $("#review_extra_ssl").replaceWith('<div id="review_extra_ssl" class="lead"></div>');                            
            }
            else {
                $("#review_extra_ssl").replaceWith('<div id="review_extra_ssl" class="lead">' 
                        + 'SSL Certificate <strong class="pull-right"> $ 24 </strong> </div>');                                            
            }
        });        
        

        /* end plan renews */        
        
    });    
    
    
    /* cycle */
    function changeCycle(){
    
        /* reset */
        $('#billing_cycle')
            .find('option')
            .remove()
            .end()
            .append('<option value="">(Select)</option>')
            .val('select');

        $("#plan_renews").replaceWith('<div id="plan_renews"></div>');
        /* end reset */
        
        var id = $("#hosting_plan").val()
        $.getJSON ('<?php echo url('json/invoices/get_prices_by_product'); ?>?id=' + id , function (data){                                                
            
            $("#billing_cycle").append('<option value="anually" '+
                    ' data-price="' + data.anually_price + '" >Anually - $' 
                    + data.anually_monthly_price + ' per month - $' 
                    + data.anually_price + ' ( Save 14%! )</option>');
            $("#billing_cycle").append('<option value="monthly" '+
                    ' data-price="' + data.monthly_price + '">Monthly - $'  
                    + data.monthly_price + ' per month </option>');            
            sumItens(); // sum items
        });    
    }    
    /* end Cycle */
    

    
    
    function sumItens(){
                
        var total = 0;    
          
        if ( $('input[name="ckb_add_migrate"]').is(':checked') ) {
            var item_total = $('input[name="ckb_add_migrate"]').val();
            total += parseFloat(item_total);    
            console.log("migrate");
        } 
        if ( $('input[name="ckb_add_ssl"]').is(':checked') ) {
            var item_total = $('input[name="ckb_add_ssl"]').val();
            total += parseFloat(item_total); 
            console.log("ssl");
        }                 
            
        total += parseFloat($('#review_product').attr("data-price"));
        
        console.log('Total:' + total);

        $("#price_total").html( total.toFixed(2) );
        
         
    }
    
</script>
@endsection

@section('content')

<div class="col-sm-16 col-xs-12">
    <form class="form form-horizontal" id="frmSend" method="POST" action="{{ url('/products/order') }}">
    {{ csrf_field() }}
    <input type="hidden" name="product_id" id="product_id" value="" />    
    <input type="hidden" name="product_periodicity" id="product_periodicity" value="" />            
    <input type="hidden" name="db_product_price" id="db_product_price" value="" />        
    
    <div class="card">
        <div class="card-header">
          Order a product
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
                                <div class="title">Select your product</div>
                                <div class="description">Select the best option for your business</div>
                            </div>
                        </a>
                    </li>                    
                    <li role="step">
                        <a href="#step2" role="tab" id="step2-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-credit-card"></div>
                            <div class="heading">
                                <div class="title">Payment</div>
                                <div class="description">Billing Information</div>
                            </div>
                        </a>
                    </li>

                    <li role="step">
                        <a href="#step3" role="tab" id="step3-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-server "></div>
                            <div class="heading">
                                <div class="title">Purchase Successful</div>
                                <div class="description">We'll have you up and running within 24 hours</div>
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
          
          
    <br />                      
          
    <div class="row">
        <div class="col-xs-12">
          <div class="card">
                  
        <div class="card-body">
        
            
            
            
<div class="panel panel-success">
    <div class="panel-heading lead">Choosing a Hosting Plan</div>
  <div class="panel-body">
      
           <div class="form-group">
              <label class="col-md-3 control-label">Hosting Plan</label>
              <div class="col-md-6">                
                  <select class="select2" name="hosting_plan" 
                          id="hosting_plan" onchange="changeCycle()" autofocus>
                      <option value="">Please select a hosting plan</option> 
                    <?php foreach ($products as $product) { ?>
                      <option value="{{ $product->product_id }}" 
                          <?php if( isset($selected_product) && $selected_product == $product->product_id) { echo 'selected'; } ?> >{{ $product->prod_name }}</option> 
                    <?php } ?>
                </select>                
                  <div class="text-danger" id="hosting_plan_validate"></div>
              </div>
            </div>
              
           <div class="form-group">
              <label class="col-md-3 control-label">Billing Cycle</label>
              <div class="col-md-6">
                <select class="select2" name="billing_cycle" id="billing_cycle">                
                  <option value="">Select...</option>                   
                </select>  
                <div class="text-danger" id="billing_cycle_validate"></div>
              </div>
            </div>
            
           <div class="form-group">
              <label class="col-md-3 control-label"></label>
              <div class="col-md-6">                
                  <div id="plan_renews" class="text-muted"></div>
              </div>
            </div>      
      
  </div>
</div>
            
        <div class="panel panel-success">
            <div class="panel-heading lead">Choose a Domain</div>
          <div class="panel-body">

                   <div class="form-group">
                      <label class="col-md-3 control-label">Domain name</label>
                      <div class="col-md-6">
                        <input type="text" id="domain_name" name="domain_name" class="form-control" placeholder="yourbusiness.com">
                        <div class="text-danger" id="domain_name_validate"></div>
                      </div>
                    </div>                 

          </div>
        </div> 
            
        <?php if (!Auth::check()) {  ?>    
        <!-- billing -->    
        <div class="panel panel-success">
            <div class="panel-heading lead">Enter Your Billing Information</div>
          <div class="panel-body">

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">Full Name</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        <div class="text-danger" id="name_validate"></div>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        <div class="text-danger" id="email_validate"></div>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" required>
                        <div class="text-danger" id="password_validate"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
          </div>
        </div>             
        <!-- end billing -->    
        <?php } ?>    
        
        <div class="panel panel-success">
            <div class="panel-heading lead">Recommended Additional Services</div>
          <div class="panel-body">
                  
                <div class="checkbox">
                    <input type="checkbox" id="ckb_add_migrate" name="ckb_add_migrate" value="60" onchange="sumItens()">
                    <label for="ckb_add_migrate">
                        &nbsp; Migrate my Website   
                        <p class="text-muted"> 
                            $ 60 up to 3 websites ( Billed once )<br />
                            We can migrate your website from the actual hosting to Cat&Mouse Hosting.
                        </p>
                    </label>                    
                </div>
                  
                <div class="checkbox">
                    <input type="checkbox" id="ckb_add_ssl" name="ckb_add_ssl" value="24"  onchange="sumItens()">
                    <label for="ckb_add_ssl">
                        &nbsp; SSL Certificate 
                        <p class="text-muted">$ 24 ( Billed Anually )</p>
                    </label>
                </div>                

          </div>
        </div>
            
            
        <div class="panel panel-success">
            <div class="panel-heading lead">Review Your Order Details</div>
          <div class="panel-body">                  
            <table class="table table-striped">
                <tr>
                    <td>
                        <div id="review_product" data-price="0" class="col-md-9"></div>
                        <div id="review_extra_migration" class="col-md-9"></div>
                        <div id="review_extra_ssl" class="col-md-9"></div>
                        <div id="review_results" class="col-md-9"></div>
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="review_results" class="lead">
                            Amount Due: 
                            <strong class="pull-right">$ <span id="price_total"></span> </strong>
                        </div>
                    </td>
                </tr>                
            </table>        

          </div>
        </div>
        
           <div class="form-group">
              <label class="col-md-4 control-label">Do you agree with our Terms and Conditions?</label>
              <div class="col-md-8">           
               
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
        
            <div class="form-footer">
                <div class="form-group">
                  <div class="col-md-2 pull-right">
                      <button type="submit" class="btn btn-primary btn-lg">Continue</button>
                  </div>
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
    </form>
</div>





        
@endsection
