@extends('layouts.dashboard')


@section('header_tags')

<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>

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
                    country: "required"
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
          
});    
    
    
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
          
        </div>
      </div>
    </div>




        
@endsection
