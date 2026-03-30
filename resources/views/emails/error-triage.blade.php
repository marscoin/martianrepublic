<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Error Triage Alert</title>
</head>
<body style="margin: 0; padding: 0; background-color: #06060c; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #06060c; padding: 40px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background-color: #12121e; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06); overflow: hidden;">

<!-- Header -->
<tr><td style="background: linear-gradient(135deg, #2a1a1a 0%, #12121e 100%); padding: 40px 40px 30px; text-align: center;">
    <img src="https://martianrepublic.org/assets/landing/img/logomarscoinwallet.png" alt="Martian Republic" width="48" style="margin-bottom: 16px;">
    <h1 style="margin: 0; font-family: 'Orbitron', sans-serif; font-size: 20px; color: #ff4444; letter-spacing: 2px;">⚠ ERROR TRIAGE ALERT</h1>
    <p style="margin: 8px 0 0; color: #8a8998; font-size: 14px;">AI-assisted error analysis for the Martian Republic</p>
</td></tr>

<!-- Error Details -->
<tr><td style="padding: 32px 40px 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #1a1a2a; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); margin-bottom: 24px;">
    <tr><td style="padding: 20px;">
        <p style="margin: 0 0 4px; color: #ff4444; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Exception</p>
        <p style="margin: 0 0 16px; color: #e4e4e7; font-size: 16px; font-weight: 600; word-break: break-all;">{{ class_basename($exceptionClass) }}</p>

        <p style="margin: 0 0 4px; color: #8a8998; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Message</p>
        <p style="margin: 0 0 16px; color: #e4e4e7; font-size: 14px; line-height: 1.5;">{{ $errorMessage }}</p>

        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 4px;">
        <tr>
            <td width="50%" style="padding-right: 8px;">
                <p style="margin: 0 0 4px; color: #8a8998; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">File</p>
                <p style="margin: 0; color: #00e4ff; font-size: 13px; font-family: 'JetBrains Mono', monospace; word-break: break-all;">{{ str_replace('/home/martianrepublic/', '', $file) }}:{{ $line }}</p>
            </td>
            <td width="50%" style="padding-left: 8px;">
                <p style="margin: 0 0 4px; color: #8a8998; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Route</p>
                <p style="margin: 0; color: #00e4ff; font-size: 13px; font-family: 'JetBrains Mono', monospace;">{{ $method }} {{ $url }}</p>
            </td>
        </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 16px;">
        <tr>
            <td width="50%" style="padding-right: 8px;">
                <p style="margin: 0 0 4px; color: #8a8998; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">User ID</p>
                <p style="margin: 0; color: #e4e4e7; font-size: 14px;">{{ $userId ?? 'Guest' }}</p>
            </td>
            <td width="50%" style="padding-left: 8px;">
                <p style="margin: 0 0 4px; color: #8a8998; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Time</p>
                <p style="margin: 0; color: #e4e4e7; font-size: 14px;">{{ $occurredAt }}</p>
            </td>
        </tr>
        </table>
    </td></tr>
    </table>
</td></tr>

<!-- AI Triage -->
<tr><td style="padding: 0 40px 32px;">
    <p style="margin: 0 0 12px; color: #00e4ff; font-size: 12px; text-transform: uppercase; letter-spacing: 2px;">🤖 AI Triage Analysis</p>
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0c1a1a; border-radius: 8px; border: 1px solid rgba(0,228,255,0.15);">
    <tr><td style="padding: 20px;">
        <p style="margin: 0; color: #d4d4d8; font-size: 14px; line-height: 1.7; white-space: pre-wrap;">{!! nl2br(e($aiSummary)) !!}</p>
    </td></tr>
    </table>
</td></tr>

<!-- Footer -->
<tr><td style="padding: 24px 40px; border-top: 1px solid rgba(255,255,255,0.06); text-align: center;">
    <p style="margin: 0; color: #8a8998; font-size: 12px;">
        Martian Republic Error Monitor &middot; Auto-routed via OpenRouter
    </p>
    <p style="margin: 8px 0 0; color: #555; font-size: 11px;">
        Duplicate errors are suppressed for {{ config('services.error_triage.cooldown_minutes') }} minutes
    </p>
</td></tr>

</table>
</td></tr>
</table>
</body>
</html>
