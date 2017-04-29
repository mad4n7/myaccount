@extends('layouts.dashboard')

@section('header_tags')
<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>



@endsection

@section('content')

<div class="col-sm-16 col-xs-12">
    <form class="form form-horizontal" id="frmSend" method="POST" action="{{ url('orders') }}">
    {{ csrf_field() }}
    <input type="hidden" name="product_id" id="product_id" value="" />    
    <input type="hidden" name="product_periodicity" id="product_periodicity" value="" />        
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
                                <div class="title">Confirm Orders</div>
                                <div class="description">Confirm your purchases</div>
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
                                <div class="description">We'll have you up and running within 24 hours</div>
                            </div>
                        </a>
                    </li>
                </ul>                
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

              <div class="col-md-4 col-sm-6" style="padding-top: 3%;">
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
                      <a type="button" href="{{ url('hosting/order') }}?product={{ $product->product_id }}"
                              class="btn btn-default btn-success btn_product" 
                              data-product-order="{{ $i }}" 
                              data-product-id="{{ $product->product_id }}"
                              data-product-price-month="{{ $product->price_month }}"
                              data-product-price-year="{{ $product->price_year }}">Select</a>
                  </div>
                </div>
              </div>
                
            <?php $i++; } ?>                  
                
            </div>
          </div>              
            </div>
            <br />
            <p class="pull-right lead">If you need more robost hosting, please contact us.</p>
        </div>
    </div>
    <!-- end  pricing table -->
    <br />
               
          
          
    
        </div>
    </div>          
          
          <!-- end content -->            
    </form>
</div>





        
@endsection
