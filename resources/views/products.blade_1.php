@extends('layouts.dashboard')



@section('header_tags')

<script>
    $(function() {
        $("#term-length").hide();
        
        /* select the product */
        $( ".btn_product" ).click(function() {
            $("#term-length").show();
            
            <?php
            $products = App\Product::all();
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
    });    
    
</script>


    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flat-admin.css') }}">
       
@endsection

@section('content')
<div class="panel panel-default">
    <div class="panel-body">
<div class="flex-center position-ref full-height">
            

            <div class="content">
                <div class="title m-b-md">
                    Awesome!
                </div>

                <div class="links">
                    <p>We look forward to working with you.</p>
                    <p>Please continue by logging into your account.</p>
                </div>
                
                <!-- Login section -->
                <div>
                    
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
            $products = App\Product::all();
            foreach ($products as $product) { 
                ?>

              <div class="col-md-4 col-sm-6">
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
                      <li><i class="icon ion-person-stalker"></i> <?php echo $product->prod_description; ?> </li>
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
                    
                </div>
                <!-- end login section -->
                
                <div class="links">
                    <p><a href="{{ route('register') }}">Don't have an account? Create one in a jiffy!</a></p>
                </div>
                <!-- Register section -->
                <div>
                    
                    
                </div>
                <!-- Register section -->
            </div>
        </div>
    </div>
    
@endsection