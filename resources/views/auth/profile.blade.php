@extends('layouts.dashboard')


@section('header_tags')

<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery.creditCardValidator.js') }}"></script>

<script>
$(function() {
        
    /* Form validation */
    $("#frmSend").validate({
            rules: {
                    email: { 
                        required: true,
                        email: true
                    },
                    name: "required",
                    address: "required",
                    phone_number: "required",
                    zip_code: "required",
                    city: "required",
                    country: "required",
                    us_state_code: "required"
            },
            messages: {
                    email: "Please, type the website address(domain name)"                    
            },
            errorPlacement: function (error, element) {
                var name = $(element).attr("name");
                error.appendTo($("#" + name + "_validate"));
            }
    });  
    
    /* Form validation */
    $("#frmSend2").validate({
            rules: {                   
                    current_password: "required",
                    password: "required",
                    password_again: {
                      equalTo: "#password"
                    }
            },
            messages: {
                               
            },
            errorPlacement: function (error, element) {
                var name = $(element).attr("name");
                error.appendTo($("#" + name + "_validate"));
            }
    });   
    
    
 
        /* Form validation */
        $("#frmSend3").validate({
                focusInvalid: true,
                rules: {
                    
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
                        }
                },
                messages: {
                        
                },
                errorPlacement: function (error, element) {
                    var name = $(element).attr("name");
                    error.appendTo($("#" + name + "_validate"));
                }
        });  
        
        
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


function deleteCard() {
    var txt;
    var r = confirm("Do you want to delete this credit card?");
    if (r == true) {
        txt = "You pressed OK!";
    } else {
        txt = "You pressed Cancel!";
    }
    document.getElementById("demo").innerHTML = txt;
}
    
</script>
@endsection

@section('content')
<div class="col-lg-12">
      <div class="card card-tab">
        <div class="card-header">
          <ul class="nav nav-tabs">
            <li role="tab1" class="active">
              <a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab" aria-expanded="true">Profile</a>
            </li>
            <li role="tab3" class="">
              <a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab" aria-expanded="false">Password</a>
            </li>
            
            <li role="tab4" class="">
              <a href="#tab4" aria-controls="tab3" role="tab" data-toggle="tab" aria-expanded="false">Billing</a>
            </li>
            
          </ul>
        </div>
        <div class="card-body no-padding tab-content">
          <div role="tabpanel" class="tab-pane active" id="tab1">
            <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                
                <div class="panel panel-default">
                  <div class="panel-body">
                    <form class="form form-horizontal" id="frmSend" method="POST" action="{{ url('profile') }}">
                    {{ csrf_field() }} 
                    
                    <div class="form-group">
                       <label for="name">Name</label>
                       <input type="name" class="form-control" name="name" id="name" placeholder="Name" value="{{ $user->name }}">
                       <div class="text-danger" id="name_validate"></div>
                   </div>                      
                    
                    <div class="form-group">
                       <label for="email">E-mail</label>
                       <input type="text" class="form-control" name="email" id="email" placeholder="E-mail" value="{{ $user->email }}">
                       <div class="text-danger" id="email_validate"></div>
                   </div>  

                    <div class="form-group">
                       <label for="phone_number">Phone Number</label>
                       <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Phone number" value="{{ $user_details->phone_number }}">
                       <div class="text-danger" id="phone_number_validate"></div>
                   </div>   
                    
                    <div class="form-group">
                       <label for="address">Address</label>
                       <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="{{ $user_details->address }}">
                       <div class="text-danger" id="address_validate"></div>
                   </div>       
                    
                    <div class="form-group">
                       <label for="address2">Address (line 2)</label>
                       <input type="text" class="form-control" name="address2" id="address2" placeholder="Address (line 2)" value="{{ $user_details->address2 }}">
                   </div>   
                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                               <label for="city">City</label>
                               <input type="text" class="form-control" name="city" id="city" placeholder="City" value="{{ $user_details->city }}">
                               <div class="text-danger" id="city_validate"></div>
                           </div>                             
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                               <label for="zip_code">Zip Code</label>
                               <input type="text" class="form-control" name="zip_code" id="zip_code" placeholder="Zip Code" value="{{ $user_details->zip_code }}">
                               <div class="text-danger" id="zip_code_validate"></div>
                           </div>                             
                        </div>                        
                    </div>
                    
                    <div class="form-group">
                       <label for="us_state_code">State</label>
                       <select class="form-control" name="us_state_code" id="us_state_code">
                           <option>Select...</option>
                        <?php foreach ($us_states as $us_state){ 
                           
                            if($user_details->us_state_code == $us_state->us_state_code ){
                                $selected = 'selected';
                            }
                            else { 
                                $selected = '';                                 
                            } 
                            ?>
                           <option value="{{ $us_state->code }}" {{ $selected }} >{{ $us_state->name }}</option>
                        <?php } ?>
                        
                      </select>
                       <div class="text-danger" id="us_state_code_validate"></div>
                   </div>                    
                    
                    <div class="form-group">
                       <label for="country">Country</label>
                       <select class="form-control" name="country" id="country">
                        <?php foreach ($countries as $country){ 
                            if(is_null($user_details->country_code) || empty($user_details->country_code)){
                                $user_details->country_code = 'US';
                            }
                            if($user_details->country_code == $country->country_code ){
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
                    
                    <br />
                    <div class="form-group">
                       <label for="company">Company</label>
                       <input type="text" class="form-control" name="company" id="company" placeholder="Company" value="{{ $user_details->company }}">
                    </div>                      
                    
                    <br />
                    <div class="form-footer">
                        <div class="form-group">
                          <div class="pull-right">
                            <button type="submit" class="btn btn-primary btn-lg">Update Profile</button>
                          </div>
                        </div>
                    </div>                      
                    </form>
                  </div>  
                </div>                    
                  
              </div>
              <div class="col-md-3"></div>
            </div>
          </div>
            
          <!-- tab 2 -->  
          <div role="tabpanel" class="tab-pane" id="tab3">
            <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                
                <div class="panel panel-default">
                  <div class="panel-body">
                    <form class="form form-horizontal" id="frmSend2" method="POST" action="{{ url('profile/password') }}">
                    {{ csrf_field() }} 
                    

                    <div class="form-group">
                       <label for="current_password">Current Password</label>
                       <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Current Password" value="">
                       <div class="text-danger" id="current_password_validate"></div>
                   </div>   
                    
                    <div class="form-group">
                       <label for="password">New Password</label>
                       <input type="password" class="form-control" name="password" id="password" placeholder="New Password" value="">
                       <div class="text-danger" id="password_validate"></div>
                   </div>       
                    
                    <div class="form-group">
                       <label for="password_again">Repeat the New Password</label>
                       <input type="password" class="form-control" name="password_again" id="password_again" placeholder="Repeat New Password" value="">
                       <div class="text-danger" id="password_again_validate"></div>
                   </div>   
   
                    <br />
                    <div class="form-footer">
                        <div class="form-group">
                          <div class="pull-right">
                            <button type="submit" class="btn btn-primary btn-lg">Change Password</button>
                          </div>
                        </div>
                    </div>                      
                    </form>
                  </div>  
                </div>                    
                  
              </div>
              <div class="col-md-3"></div>
            </div>            
              
          </div>
          <!-- end tab -->
          
          
          <!-- tab 3 -->  
          <div role="tabpanel" class="tab-pane" id="tab4">
            <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading lead">Credit Card</div>
                    <div class="panel-body">
                        <div class="lead">
                            <strong>Saved Credit Cards:</strong>
                            <table class="table table-hover">
                              <thead>
                                <tr>
                                  <th>Brand</th>
                                  <th>Name on Card</th>
                                  <th>Last 4 digits</th>
                                  <th>Actions</th>
                                </tr>
                              </thead>
                              <tbody>
                                  <?php foreach($list_cc->data as $item) { ?>
                                <tr>
                                  <th scope="row">{{ $item->brand }}</th>
                                  <td>{{ $item->name }}</td>
                                  <td>{{ $item->last4 }}</td>
                                  <td><a href="#" onclick="alert('Please contact us to delete a credit card.')">delete</a></td>
                                </tr>
                                  <?php } ?>
                              </tbody>
                            </table>                            
                        </div>
                        <hr />
                        
                    <form class="form form-horizontal" id="frmSend3" method="POST" action="{{ url('profile/cc_update') }}">
                    {{ csrf_field() }}                         
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h5 class="text-muted"> Credit Card Number</h5>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" 
                                       name="cc_number" id="cc_number" 
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
                                       placeholder="Name On The Card" />
                                <div class="text-danger" id="cc_name_validate"></div>
                            </div>
                        </div>
                    <br />
                    <p class="text-muted">*Your address will be the same as the PROFILE tab address.</p>
                    <div class="form-footer">
                        <div class="form-group">
                          <div class="pull-right">
                            <button type="submit" class="btn btn-primary btn-lg">Add New Card</button>
                          </div>
                        </div>
                    </div>                      
                    </form>                        
                    </div>
                </div>                
  
              </div>
              <div class="col-md-3"></div>
            </div>            
              
          </div>
          <!-- end tab -->          
          
        </div>
      </div>
    </div>




        
@endsection
