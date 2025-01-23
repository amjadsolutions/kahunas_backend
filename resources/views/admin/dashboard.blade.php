@extends('layouts.app')

@section('content')
    <h1>AI Prompt Dashboard</h1>

    <h2>Most Used Prompts</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Prompt</th>
                <th>Usage Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mostUsedPrompts as $prompt)
                <tr>
                    <td>{{ $prompt->user_prompt }}</td>
                    <td>{{ $prompt->usage_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Average Response Quality Score</h2>
    <div class="quality-score">
        <p><strong>Average Score:</strong> {{ $averageQualityScore }}</p>
        <p>
            Note: if you want to add more promopts, please use the endpoint /ai-response in postman. Provide content in body like example below.

<br />
            {
                "content" :"How is the weather today ?"
            }
        </p>
    </div>
@endsection

@push('styles')
    <style>
        /* Table Styling */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        
        tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Section Titles Styling */
        h2 {
            margin-top: 30px;
            font-size: 1.5em;
            color: #333;
        }

        /* Quality Score Styling */
        .quality-score p {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
        }
    </style>
@endpush
