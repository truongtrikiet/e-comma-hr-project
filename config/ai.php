<?php

return [
    'timeout' => env('AI_TIMEOUT', 60),
    'threshold' => env('AI_THRESHOLD', 95),
    'threshold_name' => env('AI_THRESHOLD_NAME', 90),
    'max_output_tokens' => env('AI_MAX_OUTPUT_TOKENS', 4096),
    'batch_size' => env('AI_BATCH_SIZE', 10),
    'prompts' => [
        'cv_screening' => <<<PROMPT
            Analyze one CV for a Vietnamese education recruitment system.

            Position type: {{position_type}}
            CV:
            {{cv_text}}

            Rules:
            - Use only explicit CV content. Do not guess missing data.
            - If unclear or absent, use null or [].
            - "teacher" means teaching roles. Consider teaching experience, subjects, education background, school exposure.
            - "staff" means non-teaching roles. Consider administration, HR, finance, IT, admissions, support, school operations, and transferable skills.
            - Set is_suitable=false if the CV is too short or unclear.
            - Return valid JSON only, no markdown.

            Use this JSON shape and replace type labels with real values:
            {
              "position_type": "{{position_type}}",
              "candidate": {
                "name": "string | null",
                "email": "string | null",
                "phone_number": "string | null"
              },
              "is_suitable": true | false,
              "recommended_roles": [
                {
                  "role": "string",
                  "confidence": "high | medium | low",
                  "evidence": ["string"]
                }
              ],
              "experience_summary": {
                "total_years": number | null,
                "relevant_experience": ["string"]
              },
              "promotion_potential": {
                "is_potential_candidate": true | false | null,
                "positive_factors": ["string"],
                "missing_or_unclear_factors": ["string"]
              },
              "notes_for_hr": ["string"],
              "final_summary": "string"
            }
        PROMPT,
        'cv_screening_batch' => <<<PROMPT
            Analyze up to 10 CVs for a Vietnamese education recruitment system.

            Position type for all CVs: {{position_type}}
            CV batch JSON:
            {{cv_batch_json}}

            Each input item has:
            - cv_id: stable id that MUST be copied into the matching result
            - cv_text: extracted CV text

            Rules:
            - Return exactly one result for every input cv_id, in the same order.
            - Analyze each CV independently. Never mix information between CVs.
            - Use only explicit CV content. Do not guess missing data.
            - If unclear or absent, use null or [].
            - "teacher" means teaching roles. Consider teaching experience, subjects, education background, school exposure.
            - "staff" means non-teaching roles. Consider administration, HR, finance, IT, admissions, support, school operations, and transferable skills.
            - Set is_suitable=false if a CV is too short or unclear, and explain briefly in notes_for_hr.
            - Return valid JSON only, no markdown.

            Use this JSON shape and replace type labels with real values:
            {
              "position_type": "{{position_type}}",
              "results": [
                {
                  "cv_id": "string",
                  "candidate": {
                    "name": "string | null",
                    "email": "string | null",
                    "phone_number": "string | null"
                  },
                  "is_suitable": true | false,
                  "recommended_roles": [
                    {
                      "role": "string",
                      "confidence": "high | medium | low",
                      "evidence": ["string"]
                    }
                  ],
                  "experience_summary": {
                    "total_years": number | null,
                    "relevant_experience": ["string"]
                  },
                  "promotion_potential": {
                    "is_potential_candidate": true | false | null,
                    "positive_factors": ["string"],
                    "missing_or_unclear_factors": ["string"]
                  },
                  "notes_for_hr": ["string"],
                  "final_summary": "string"
                }
              ]
            }
        PROMPT
    ],
];
