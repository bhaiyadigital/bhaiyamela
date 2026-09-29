@foreach($projects as $project)


<div class="h-auto">
    @include('frontend.partials.project_card', ['project' => $project])

</div>
@endforeach