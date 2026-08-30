<strong>Dear {{ $toName }},</strong>
<br>
<br>


<p>
    {!!  $body !!}
</p>
<a href="https://{{$application->domain_url}}/login">https://{{$application->domain_url}}/login</a>

<p>
    @if(!empty($application->playstore_url))
You can also download our application from
    <a href="{{$application->playstore_url}}">Google Play Store</a> and 
    <a href="{{$application->appstore_url}}">App Store</a> and send money from app easily.
@endif
</p>

<p>
    Thank You
</p>
