<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Interview Invitation</title>
    </head>
    <body>

        <h2>
            Hello {{ $candidateScreening->candidate_name ?? 'Candidate' }},
        </h2>

        <p>
            On behalf of <strong>{{ $candidateScreening->school->name }}</strong>,
            we are pleased to invite you to attend an interview.
        </p>

        <hr>

        <p>
            <strong>📅 Interview Time:</strong><br>
            {{ $interview['time'] ?? 'To be confirmed' }}
        </p>

        <p>
            <strong>📍 Interview Location:</strong><br>
            {{ $interview['location'] ?? 'To be confirmed' }}
        </p>

        @if(!empty($interview['note']))
            <p>
                <strong>📝 Additional Information:</strong><br>
                {{ $interview['note'] }}
            </p>
        @endif

        <hr>

        <p>
            Please make sure to arrive on time.  
            If you have any questions, feel free to contact us.
        </p>

        <br>

        <p>
            Best regards,<br>
            <strong>{{ $candidateScreening->school->name }}</strong><br>
            E-Comma HRM System
        </p>

    </body>
</html>