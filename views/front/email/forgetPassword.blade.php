<h1>{{tran('Forget Password Email')}}</h1>
   
{{tran('You can reset password from bellow link')}}:
<a href="{{ route('reset.password.get', $token) }}">{{tran('Reset Password')}}</a>