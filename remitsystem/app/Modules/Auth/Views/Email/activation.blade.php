
<strong>Dear {{ $toName }},</strong>

<p>
    {!!  $body !!}
</p>

<p>
Once activated, access our web system at <a href="https://{{$application->domain_url}}/login">https://{{$application->domain_url}}/login</a>.<br><br>
@if(!empty($application->playstore_url))
You can also download our application from
    <a href="{{$application->playstore_url}}">Google Play Store</a> and 
    <a href="{{$application->appstore_url}}">App Store</a> and send money from app easily.
@endif
</p>

<p>
    Thank You<br>
    {{getCompanyName()}}
</p>
