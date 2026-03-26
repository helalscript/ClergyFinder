<div style="font-family: Helvetica, Arial, sans-serif; min-width: 1000px; overflow: auto; line-height: 1.5; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center; border-bottom: 1px solid #eeeeee; padding-bottom: 10px;">
            <strong style="font-size: 1.2em; color: #00466a;">New Prayer Request</strong>
        </div>
        <p style="font-size: 1em; color: #333333; margin-top: 20px;">Hello,</p>
        <p style="font-size: 1em; color: #555555;">A seeker has submitted a new prayer request.</p>
        <div style="background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0;">
            <p style="margin: 0; color: #333;"><strong>Message:</strong></p>
            <p style="margin: 5px 0 0 0; color: #555;">{{ $prayerRequest->message }}</p>
        </div>
        <p style="font-size: 0.95em; color: #555555;">
            <a href="{{ $frontendUrl }}/clergy/prayer-requests/{{ $prayerRequest->id }}" style="color: #00466a;">View and reply to this request</a>
        </p>
        <p style="font-size: 0.9em; color: #555555; margin-top: 20px;">Best regards,<br>{{ config('app.name') }}</p>
    </div>
</div>
