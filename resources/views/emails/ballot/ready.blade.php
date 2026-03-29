<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Ballot is Ready</title>
</head>
<body style="margin: 0; padding: 0; background-color: #06060c; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #06060c; padding: 40px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background-color: #12121e; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06); overflow: hidden;">

<!-- Header -->
<tr><td style="background: linear-gradient(135deg, #1a1a2a 0%, #12121e 100%); padding: 40px 40px 30px; text-align: center;">
    <img src="https://www.martianrepublic.org/images/logo.png" alt="Martian Republic" width="48" style="margin-bottom: 16px;">
    <h1 style="margin: 0; font-family: 'Orbitron', sans-serif; font-size: 22px; color: #00e4ff; letter-spacing: 2px;">YOUR BALLOT IS READY</h1>
    <p style="margin: 8px 0 0; color: #8a8998; font-size: 14px;">Cast your vote on the Marscoin blockchain</p>
</td></tr>

<!-- Body -->
<tr><td style="padding: 32px 40px;">
    <p style="color: #e4e4e7; font-size: 16px; line-height: 1.6; margin: 0 0 24px;">
        Citizen {{ $user->fullname }},
    </p>
    <p style="color: #e4e4e7; font-size: 16px; line-height: 1.6; margin: 0 0 24px;">
        Your anonymous ballot for <strong style="color: #00e4ff;">{{ $proposalTitle }}</strong> has been confirmed on the Marscoin blockchain. Your vote is ready to be cast.
    </p>

    <!-- Proposal Card -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #1a1a2a; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); margin-bottom: 24px;">
    <tr><td style="padding: 20px;">
        <p style="margin: 0 0 4px; color: #8a8998; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Proposal #{{ $proposalId }}</p>
        <p style="margin: 0 0 12px; color: #e4e4e7; font-size: 18px; font-weight: 600;">{{ $proposalTitle }}</p>
        <p style="margin: 0; color: #8a8998; font-size: 13px;">
            Ballot TX: <code style="color: #00e4ff; font-size: 12px;">{{ substr($ballotTxid, 0, 16) }}...{{ substr($ballotTxid, -8) }}</code>
        </p>
    </td></tr>
    </table>

    <!-- CTA Button -->
    <table width="100%" cellpadding="0" cellspacing="0">
    <tr><td align="center" style="padding: 8px 0 24px;">
        <a href="{{ $voteUrl }}" style="display: inline-block; background-color: #c84125; color: #ffffff; text-decoration: none; padding: 16px 48px; border-radius: 8px; font-size: 16px; font-weight: 700; letter-spacing: 2px; font-family: 'Orbitron', sans-serif;">
            CAST YOUR VOTE
        </a>
    </td></tr>
    </table>

    <p style="color: #8a8998; font-size: 14px; line-height: 1.6; margin: 0;">
        Your vote is completely anonymous. The cryptographic shuffle ensures that no one &mdash; not even the system &mdash; can trace your vote back to you.
    </p>
</td></tr>

<!-- Footer -->
<tr><td style="padding: 24px 40px; border-top: 1px solid rgba(255,255,255,0.06); text-align: center;">
    <p style="margin: 0; color: #8a8998; font-size: 12px;">
        The Martian Republic &middot; Securing free and fair elections through cryptography
    </p>
    <p style="margin: 8px 0 0; color: #555; font-size: 11px;">
        You received this email because you requested a ballot on martianrepublic.org
    </p>
</td></tr>

</table>
</td></tr>
</table>
</body>
</html>
