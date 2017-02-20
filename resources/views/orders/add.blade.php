@extends('layouts.dashboard')

@section('content')

<div class="col-sm-16 col-xs-12">
    <div class="card">
        <div class="card-header">
          Order
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
                        <b>Step1</b> : Select your plan.
                    </div>
                    <div role="tabpanel" class="tab-pane" id="step2">
                        <b>Step2</b> : Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore
                    </div>
                    <div role="tabpanel" class="tab-pane" id="step3">
                        <b>Step3</b> : Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum
                    </div>
                    <div role="tabpanel" class="tab-pane" id="step4">
                        <b>Step4</b> : Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequa
                    </div>
                </div>
            </div>
            </div>
            </div>    
          
          <!-- pricing table -->
        <div class="row">
            <div class="col-xs-12">
              <div class="card">
          <div class="card-body no-padding">
            <div class="row no-gap">
             
            <?php foreach ($products as $product) { ?>
                
              <div class="col-md-3 col-sm-6">
                <div class="pricing-table no-border-left">
                  <div class="pricing-heading">
                    <div class="title">{{ $product->prod_name }}</div>
                    <div class="price">
                      <div class="title">{{ $product->price_month }}<span class="sign">$</span></div>
                      <div class="subtitle">per month</div>
                    </div>
                    
                  </div>
                  <div class="pricing-body">
                    <ul class="description">                      
                      <li><i class="icon ion-person-stalker"></i> <?php echo $product->prod_description; ?><span class="small">Users</span></li>
                      <li><i class="icon ion-ios-chatboxes-outline"></i> or $ {{ $product->price_year }} <span class="small">/ year</span></li>
                    </ul>
                  </div>
                  <div class="pricing-footer">
                      <a class="btn btn-default btn-success" href="#">Select</a>
                  </div>
                </div>
              </div>
            <?php } ?>  
                
            </div>
          </div>
        </div>
        </div>
        </div>
          <!-- end  pricing table -->
          
          
          <!-- end content -->
            
            
        </div>
    </div>
</div>




        
@endsection
