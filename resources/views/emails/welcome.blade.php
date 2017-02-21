<p>Dear <strong>{{$name}}</strong>,</p>
<p><strong><span style="color: #0033cc; font-size: large;">Welcome to Cat&Mouse!</span></strong></p>
<p>Thank you for choosing us. Your account has been successfully created.</p>
<p>Please click the link below to verify your email address:</p>
<p><a href="{{ url('safe/token/confirm'.'/'.$token) }}">{{ url('/login/token'.'/'.$token) }}</a></p>
<br />
<p>E-mail/login: <strong>{{$email}}</strong>
    <br />Password: <strong>(typed on our website)</strong></p>
<p>To sign in, visit {{url('/')}}</p>
<p>Regards, <br />
    Cat&Mouse</p>