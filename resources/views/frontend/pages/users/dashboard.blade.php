@extends('frontend.pages.users.master')
@section('sub-content')
  <!--Start Sidebar + Content -->
  <div class="container">
    <h2>Welcome {{$user->first_name. '' .$user->last_name}}</h2>
    <p>You can change your profile and every information here..</p>
    <div class="row">
      <div class="col-md-4">
        <div class="card card-body mt-2 pointer" onclick=" location.href='{{ route('user.profile') }}' ">
            <h3>User Profile</h3>
        </div>

      </div>

    </div>
  </div>

@endsection
