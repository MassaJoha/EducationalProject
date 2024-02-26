<h1>Your Code is:</h1>
<p>Please click the following link to verify your email:</p>
<a href="{{ url('/verify/'.$otp) }}">Verify Email</a>
<p>Or your code for verfiy your Email is:</p>
<p>{{ $otp }}</p>