{{-- Form --}}
@if ($project->exists)
  <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" novalidate>
    @method('PUT')
  @else
    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" novalidate>
@endif


  @csrf

  <div class="row pb-3">
    {{-- PUBLISHED  --}}
    <div class="col-12 d-flex justify-content-end">
      <div class="mt-5">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published"
          @if(old('is_published', $project->is_published)) checked @endif>
          <label class="form-check-label" for="is_published">Published</label>
        </div>
      </div>
    </div>

    {{-- TITLE  --}}  
    <div class="col-6">
      <div class="my-4 w-75">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
        name="title" required value="{{ old('title', $project->title) }}">
        @error('title')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @else
        <small class="text-muted">Enter title</small>
        @enderror
      </div>
    </div>

    {{-- SLUG PREVIEW  --}}  
    <div class="col-5">
      <div class="my-4 w-75">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" class="form-control" id="slug"
        name="slug" disabled value="{{ Str::slug(old('slug', $project->slug), '-') }}">
      </div>
    </div>   


    {{-- IMAGE  --}}
    <div class="col-5">
      <div class="mb-5">
        <label for="image" class="form-label">image</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
        name="image" value="{{ old('image', $project->image) }}">
        @error('image')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
        @else
        <small class="text-muted">Enter image Url</small>
        @enderror 
      </div>
    </div>

    {{-- IMAGE PREVIEW --}}
    {{-- <div class="col-4">
      <div class="mb-5">
        <img src="{{ old('image', $project->image ?? 'https://marcolanci.it/utils/placeholder.jpg') }}" id="preview"
          alt="preview" class="img-fluid" width="100">
      </div>
    </div> --}}    

    {{-- DESCRIPTION  --}}
    <div class="col-12">
      <div class="mb-3 w-50">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" cols="50" rows="8"
          class="form-control @error('description') is-invalid @enderror">{{ old('description', $project->description) }}</textarea>
          @error('description')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
        @else
        <small class="text-muted">Enter Description</small>
        @enderror 
      </div>
    </div>

    {{-- URL  --}}
    <div class="col-6">
      <div class="my-4">
        <label for="url" class="form-label">Url Search</label>
        <input type="url" class="form-control @error('url') is-invalid @enderror" id="url"
        name="url" value="{{ old('url', $project->url) }}">
        @error('url')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
        @else
        <small class="text-muted">Enter Search Url</small>
        @enderror 
      </div>
    </div>

    <hr>
    <div class="d-flex justify-content-between">
      <a class="btn btn-secondary" href="{{route('admin.projects.index')}}">Indietro</a>
      <button type="submit" class="btn btn-success">Save</button>
    </div>
  </div>
  
</form>

@section('scripts')
 @vite(['resources/js/preview-slag.js'])    
@endsection