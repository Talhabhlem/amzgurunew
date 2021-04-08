<div>
    <p>Dear @if(!empty($user->name))  {{$user->name}} @else {{$user->first_name.' '.$user->last_name}} @endif,</p>
    <br/>
    <p>
        Your account on <strong>EcommElite</strong> has been created.
    </p>
    <br/>
    <p><strong>Email:</strong> {{$user->email}}</p>
    <p><strong>Password</strong>: {{$pass}}</p>
    <p>Go to this link {!!url('auth/login')!!} to login to your account.</p>
    <p>You can reset your password on {!! url('password/email') !!}</p>
    <br/><br/>
    <p>Regards,</p>
    <p><strong>EcommElite</strong></p>
    <br/><br/>
</div>