@extends('layouts.dashboard')

@section('header_tags')
<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery.creditCardValidator.js') }}"></script>


<script>
    $(function() {               
        $("#review_extra_migration_details").hide();
        $("#div_website_package").hide();
        
        /* tooltips */
        var tooltips = $( "[title]" ).tooltip({
          position: {
            my: "left top",
            at: "right+5 top-5",
            collision: "none"
          }
        });        
        /* tooltips */
        
        $("#term-length").hide();
        
        var urlParams = new URLSearchParams(window.location.search);
        var product = urlParams.get('product');
        if(product > 0){
            changeCycle();
        }
        
        
        /* Form validation */
        $("#frmSend").validate({
                focusInvalid: true,
                rules: {
                    
                        <?php                                              
                        if (!Auth::check() || 
                        (isset($user_tmp) && empty($user_tmp->card_id) ) ) { 
                        ?>  
                        name: "required",
                        email: "required",
                        password: "required",
                        password_confirmation: {
                          equalTo: "#password"
                        },
                        cc_name: "required",
                        cc_cvv:  {
                            required: true,
                            number: true
                        },
                        cc_ex_year: {
                            required: true,
                            number: true
                        },
                        cc_ex_month: {
                            required: true,
                            number: true
                        },
                        cc_number: {
                            required: true,
                            number: true         
                        },
                        phone_number: "required",
                        address: "required",
                        city: "required",
                        zip_code: {
                            required: true,
                            number: true
                        },
                        us_state_code: "required",
                                
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
            var save_percent = "";
            
            
            $.getJSON ('<?php echo url('json/invoices/get_renews_on_by_billing_cycle'); ?>?type=' + type , function (str_date){                                                

                $.getJSON ('<?php echo url('json/invoices/get_prices_by_product'); ?>?id=' + product_id , function (data){                                                

                    if(type == 'monthly'){
                        price = data.monthly_price;
                        plan_renews = "( Renews Monthly )";                                                
                        $('#ckb_website_package').trigger('click');                                  
                        $("#div_website_package").hide();
                    }
                    else {
                        price = data.annually_price;
                        plan_renews = "( Renews Annually )";
                        save_percent = ' ( Save 14 % )';
                        $("#div_website_package").show();
                    }               
                    /* When it will renew) */
                    $("#plan_renews").replaceWith('<div id="plan_renews"  class="text-muted">Plan renews ' + str_date + ' at $ ' + price  + save_percent + '</div>');                                           
                    $('#db_product_price').val(price); //defines produt price
                    $('#product_id').val(product_id);   //defines product id
                    $('#product_periodicity').val(type); // periodicity
                    
                    /* Review section */        
                    $("#review_product").replaceWith('<div id="review_product" data-price="' + price + '" class="lead">' 
                            + data.product_name  + ' <strong class="pull-right"> $ ' + price +' </strong> ' + plan_renews + ' </div>');                    
                    sumItems();
                });
            }); 
        });    
        
        /**
        * Real time items
         */

        $( "#ckb_website_package" ).change(function() {
            sumItems(); // sum items
            var checked_input = $(this).is(':checked');
            /* div review migration */    
            if(!checked_input){
                $("#review_extra_wordpress_website").replaceWith('<div id="review_extra_wordpress_website" class="lead"></div>');                            
            }
            else {
                $("#review_extra_wordpress_website").replaceWith('<div id="review_extra_wordpress_website" class="lead">' 
                        + 'WordPress Website* <strong class="pull-right"> $ 350.00 </strong> </div>');                                            
            }
        });          
        
        $( "#ckb_add_migrate" ).change(function() {
            /* div review migration */      
            sumItems(); // sum items
            var checked_input = $(this).is(':checked');
            if(!checked_input){
                $("#review_extra_migration").replaceWith('<div id="review_extra_migration" class="lead">' 
                                    + '</div>');                
                $("#review_extra_migration_details").hide();
            }
            else {
                $("#review_extra_migration").replaceWith('<div id="review_extra_migration" class="lead">' 
                        + 'Migrate Website(s) <strong class="pull-right"> $ 60.00 </strong> </div>');                            
                $("#review_extra_migration_details").show();
            }
        });
        
        /* SSL */
        $( "#ckb_add_ssl" ).change(function() {
            sumItems(); // sum items
            var checked_input = $(this).is(':checked');
            /* div review migration */    
            if(!checked_input){
                $("#review_extra_ssl").replaceWith('<div id="review_extra_ssl" class="lead"></div>');                            
            }
            else {
                $("#review_extra_ssl").replaceWith('<div id="review_extra_ssl" class="lead">' 
                        + 'SSL Certificate (Renews Annually) <strong class="pull-right"> $ 24.00 </strong> </div>');                                            
            }
        });  
        
        /* backups */
        $( "#ckb_add_backuppro" ).change(function() {
            sumItems(); // sum items
            var checked_input = $(this).is(':checked');
            /* div review migration */    
            if(!checked_input){
                $("#review_extra_backup").replaceWith('<div id="review_extra_backup" class="lead"></div>');                            
            }
            else {
                $("#review_extra_backup").replaceWith('<div id="review_extra_backup" class="lead">' 
                        + 'Backup Pro (Renews Annually) <strong class="pull-right"> $ 36.00 </strong> </div>');                                            
            }
        });          
        /**
        * END of Real Time Items
         */
        

        /* end plan renews */        
        
        
        /* cc flag */
        $('#cc_number').validateCreditCard(function(result) {
            $('.log').html('Card type: ' + (result.card_type == null ? '-' : result.card_type.name)
                     + '<br>Valid: ' + result.valid
                     + '<br>Length valid: ' + result.length_valid
                     + '<br>Luhn valid: ' + result.luhn_valid);
             if(result.card_type == null){
                $('#creditcard_flag').replaceWith('<div id="creditcard_flag"><img src="<?php echo asset('images/payments/no-card.png'); ?>"  style="height: 48px; opacity: .3;"  class="img-rounded" /></div>')
             }
             else if(result.card_type.name == 'visa' || result.card_type.name == 'visa_electron') {
                 $('#creditcard_flag').replaceWith('<div id="creditcard_flag"><img src="<?php echo asset('images/payments/icon-visa-64px.png'); ?>"  style="height: 48px; "  class="img-rounded" /></div>')
             }
             else if(result.card_type.name == 'amex') {
                 $('#creditcard_flag').replaceWith('<div id="creditcard_flag"><img src="<?php echo asset('images/payments/icon-amex-64px.png'); ?>"  style="height: 48px; "  class="img-rounded" /></div>')
             }   
             else if(result.card_type.name == 'mastercard' || result.card_type.name == 'maestro' ) {
                 $('#creditcard_flag').replaceWith('<div id="creditcard_flag"><img src="<?php echo asset('images/payments/icon-mastercard-64px.png'); ?>"  style="height: 48px; "  class="img-rounded" /></div>')
             }             
             else {
                $('#creditcard_flag').replaceWith('<div id="creditcard_flag"><img src="<?php echo asset('images/payments/other-card.png'); ?>"  style="height: 48px; opacity: .3;"  class="img-rounded" /></div>')
             }             
             console.log(result.card_type.name);
        });        
        /* cc flag */
        
        
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
            
            $("#billing_cycle").append('<option value="annually" '+
                    ' data-price="' + data.annually_price + '" >Annually - $' 
                    + data.annually_monthly_price + ' per month - $' 
                    + data.annually_price + ' ( Save 14%! )</option>');
            $("#billing_cycle").append('<option value="monthly" '+
                    ' data-price="' + data.monthly_price + '">Monthly - $'  
                    + data.monthly_price + ' per month </option>');            
            sumItems(); // sum items
        });    
    }    
    /* end Cycle */
    

    
    
    function sumItems(){
                
        var total = 0;    
          
        if ( $('input[name="ckb_website_package"]').is(':checked') ) {
            var item_total = $('input[name="ckb_website_package"]').val();
            total += parseFloat(item_total);    
            //console.log("migrate");
        }          
        if ( $('input[name="ckb_add_migrate"]').is(':checked') ) {
            var item_total = $('input[name="ckb_add_migrate"]').val();
            total += parseFloat(item_total);    
            //console.log("migrate");
        } 
        if ( $('input[name="ckb_add_ssl"]').is(':checked') ) {
            var item_total = $('input[name="ckb_add_ssl"]').val();
            total += parseFloat(item_total); 
            //console.log("ssl");
        }  
        if ( $('input[name="ckb_add_backuppro"]').is(':checked') ) {
            var item_total = $('input[name="ckb_add_backuppro"]').val();
            total += parseFloat(item_total); 
            //console.log("backup");
        }         
            
        total += parseFloat($('#review_product').attr("data-price"));
        
        //console.log('Total:' + total);

        $("#price_total").html( total.toFixed(2) );
        
         
    }
    
</script>
<style>
.credit-card-div  span {
    padding-top:10px;
        }
.credit-card-div img {
    padding-top:30px;
}
.credit-card-div .small-font {
    font-size:9px;
}
.credit-card-div .pad-adjust {
    padding-top:10px;
}
</style>
@endsection

@section('content')

<div class="col-sm-16 col-xs-12">
    <form class="form form-horizontal" id="frmSend" method="POST" action="{{ url('/hosting/order') }}">
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
                    <li role="step">
                        <a href="#step1" role="tab" id="step1-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-check"></div>
                            <div class="heading">
                                <div class="title">Select your product</div>
                                <div class="description">Select the best option for your business</div>
                            </div>
                        </a>
                    </li>                    
                    <li role="step" class="active">
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
            
        <?php
        // dont show if is authenticated
        if (!Auth::check() || 
                        (isset($user_tmp) && empty($user_tmp->card_id) ) ) {
        ?>    
        <!-- billing -->  
        
        <div class="panel panel-success">
            <div class="panel-heading lead">Enter Your Billing Information</div>
          <div class="panel-body">        
        <div class="row">
            <!-- section 1 -->
            <div class="col-md-6">
                <?php 
                // if not set show these fields for the first time
                if(! isset($user_tmp)) { ?>
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">Full Name (First, Last)</label>

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
                <?php } //end ?>

                    <div class="form-group">
                       <label for="phone_number" class="col-md-4 control-label">Phone Number</label>
                       <div class="col-md-6">
                            <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Phone number" value="">
                            <div class="text-danger" id="phone_number_validate"></div>
                       </div>                       
                   </div>   
                    
                    <div class="form-group">
                       <label for="address" class="col-md-4 control-label">Address</label>
                       <div class="col-md-6">
                            <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="">
                            <div class="text-danger" id="address_validate"></div>
                       </div>                       
                   </div>       
                
                    <div  class="form-group">
                        <label for="city" class="col-md-4 control-label">City</label>
                           <div class="col-md-6">
                                <input type="text" class="form-control" name="city" id="city" placeholder="e.g. Los Angeles" value="">
                                <div class="text-danger" id="city_validate"></div>
                           </div>                           
                    </div>
                       
                       <div  class="form-group">
                           <label for="zip_code" class="col-md-4 control-label">Zip Code</label>                               
                               <div class="col-md-6">
                                    <input type="text" class="form-control" name="zip_code" id="zip_code" placeholder="Zip Code" value="">
                                    <div class="text-danger" id="zip_code_validate"></div> 
                               </div>                                                           
                        </div>                         
                    
                    
                    <div class="form-group">
                       <label for="country" class="col-md-4 control-label">State</label>
                       <div class="col-md-6">
                            <select class="form-control" name="us_state_code" id="us_state_code">
                                <option>Select...</option>
                             <?php foreach ($us_states as $us_state){?>
                                <option value="{{ $us_state->name }}">{{ $us_state->name }}</option>
                             <?php } ?>
                            </select>
                           <div class="text-danger" id="us_state_code_validate"></div>
                       </div>                       
                   </div>                    
                    <br />
                    <div class="form-group">
                       <label for="country" class="col-md-4 control-label">Country</label>
                       <div class="col-md-6">
                            <select class="form-control" name="country" id="country">
                             <?php foreach ($countries as $country){
                                    if($country->country_code == 'US' ){
                                        $selected = 'selected';
                                    }
                                    else { 
                                        $selected = '';                                 
                                    }                                  
                                 ?>
                                <option value="{{ $country->country_code }}" {{ $selected }} >{{ $country->country_name }}</option>
                             <?php } ?>                        
                             </select>
                           <div class="text-danger" id="country_validate"></div>
                       </div>                       
                   </div> 
                    
                
            </div>
            <!-- / section 1 -->
            
            <!-- section 2 -->
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading lead"><i class="fa fa-lock" aria-hidden="true"></i> Credit Card</div>
                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h5 class="text-muted"> Credit Card Number</h5>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" 
                                       name="cc_number" id="cc_number"
                                       minlength="13" maxlength="19"
                                       placeholder="" />
                                <div class="text-danger" id="cc_number_validate"></div>
                            </div>
                            
                        </div>
                        <div class="row ">
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <span class="help-block text-muted small-font"> Expiry Month</span>
                                <input type="text" class="form-control" 
                                       name="cc_ex_month" id="cc_ex_month" 
                                       placeholder="MM" maxlength="2" />
                                <div class="text-danger" id="cc_ex_month_validate"></div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <span class="help-block text-muted small-font">  Expiry Year</span>
                                <input type="text" class="form-control" 
                                       name="cc_ex_year" id="cc_ex_year" 
                                       placeholder="YY" maxlength="2" />
                                <div class="text-danger" id="cc_ex_year_validate"></div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <span class="help-block text-muted small-font">  Security Code</span>
                                <input type="text" class="form-control" 
                                       name="cc_cvv" id="cc_ccv" 
                                       placeholder="CVV" maxlength="4"  />
                                <div class="text-danger" id="cc_cvv_validate"></div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <div id="creditcard_flag"></div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12 pad-adjust">

                                <input type="text" class="form-control" 
                                       name="cc_name" id="cc_name" 
                                       placeholder="Name On Card" />
                                <div class="text-danger" id="cc_name_validate"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>            
            <!-- / section 2 -->
        </div>
   
          </div>
        </div>             
        <!-- end billing -->    
        <?php } ?>    
        
        <div class="panel panel-success">
            <div class="panel-heading lead">Recommended Additional Services</div>
          <div class="panel-body">
                <div class="checkbox" id="div_website_package">
                    <input type="checkbox" id="ckb_website_package" name="ckb_website_package" value="350" onchange="sumItems()" />
                    <label for="ckb_website_package">
                        &nbsp; WordPress Website   
                        <p class="text-muted"> 
                            $ 350 Wordpress Website*<br />
                            *Theme customization only. Four (4) page templates maximum. Excludes e-commerce. Must pay annual hosting.
                        </p>
                    </label>                    
                </div>    
                <br style="clear: both;" />              
                <div class="checkbox">
                    <input type="checkbox" id="ckb_add_migrate" name="ckb_add_migrate" value="60" onchange="sumItems()" />
                    <label for="ckb_add_migrate">
                        &nbsp; Migrate Website(s)
                        <p class="text-muted"> 
                            $ 60 up to 3 websites (Billed once)<br />
                            If your domain is currently hosted elsewhere, we can move it to Cat & Mouse Co. for you.
                        </p>
                    </label>                    
                </div>
                <div id="review_extra_migration_details" class="col-md-9">
                    <div class="form-group">
                      <label for="comment">Website(s) to migrate:</label>
                      <textarea class="form-control" rows="5" 
                                name="migration_domains"
                                id="migration_domains"></textarea>
                    </div>
                </div>
              <br style="clear: both;" />
                <div class="checkbox">
                    <input type="checkbox" id="ckb_add_backuppro" name="ckb_add_backuppro" value="36"  onchange="sumItems()">
                    <label for="ckb_add_backuppro">
                        &nbsp; Backup Pro
                        <p class="text-muted">
                            $ 36 (Billed Annually)<br />
                            Recover files from long, medium, and short term backup archives, giving you a greater chance of finding the version you are looking for, instead of multiple copies of the same version.
                        </p>
                    </label>
                </div>              
              
                <div class="checkbox">
                    <input type="checkbox" id="ckb_add_ssl" name="ckb_add_ssl" value="24"  onchange="sumItems()">
                    <label for="ckb_add_ssl">
                        &nbsp; SSL Certificate 
                        <p class="text-muted">$ 24 (Billed Annually)</p>
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
                        <div id="review_extra_wordpress_website" class="col-md-9"></div>
                        <div id="review_extra_migration" class="col-md-9"></div>
                        <div id="review_extra_backup" class="col-md-9"></div>
                        <div id="review_extra_ssl" class="col-md-9"></div>
                        <div id="review_results" class="col-md-9"></div>
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="review_results" class="lead pull-right">
                            Amount Due:&nbsp; 
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
                        &nbsp; Yes, I agree with the Cat&Mouse <a href="{{ url('/terms_conditions') }}" 
                                                                  target="_new">Terms and Conditions</a>.
                    </label>
                    <br />
                    <div class="text-danger" 
                         style="font-size: x-large; font-weight: bold;" 
                         id="terms_conditions_validate"></div>
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
