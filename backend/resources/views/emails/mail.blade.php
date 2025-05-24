@component('mail::message')
<div style="text-align: center;">
    <h2>Hi {{ $full_name }}!</h2>
    <p><strong>You need to input grades for this semester.</strong></p>
</div>

<br>

<p style="text-align: center;">
    Please log in to your instructor dashboard and submit the grades for your assigned subjects before <strong>{{ \Carbon\Carbon::parse($deadline)->format('F j, Y') }}</strong>.
</p>

<br>

@if(!empty($subjects) && count($subjects) > 0)
<p>Here are your assigned subjects:</p>
<ul>
    @foreach ($subjects as $subject)
        <li>{{ $subject->name }}</li> {{-- Adjust 'name' based on your Subject model --}}
    @endforeach
</ul>
@else
<p>You currently have no subjects assigned.</p>
@endif

<br>

<div style="display: flex; justify-content: center;">
    <a href="https://frontend-folder.test/setup.php?id={{ $instructor_id ?? '' }}"
        style="padding: 10px 20px; background-color: #f1f1f0; color: #3a8aec; text-decoration: none; border-radius: 5px;">
        Go to Grade Submission
    </a>
</div>

<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
