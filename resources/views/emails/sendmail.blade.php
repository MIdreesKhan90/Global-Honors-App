<!doctype html>
<html lang="en">
<body>
Hi!
<p>{{ $content['fullName'] }}; has sent you a message via Contact Form Submission!</p>
<p>Email: {{ $content['email'] }}</p>
<p>Phone: {{ $content['phoneNumber'] }}</p>
Agree to Terms & Conditions and Privacy Policy: {{ $content['privacyAgreement'] }}<br/>
<p>Message: {{ $content['message'] }}</p>
</body>
</html>
