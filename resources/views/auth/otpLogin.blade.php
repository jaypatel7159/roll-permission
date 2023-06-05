@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('OTP Login') }}</div>

                    <div class="card-body">

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert"> {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('otp.generate') }}">
                            @csrf

                            <div class="row mb-3">



                                {{-- <div class="col-md-5">
                                    <select name="countryCode" id="" class="form-control">

                                        <option data-countryCode="US" value="+91">IND (+91)</option>
                                        <optgroup label="Other countries">
                                            <option data-countryCode="DZ" value="+44">United Kingdom (+44)</option>
                                            <option data-countryCode="AD" value="+376">Andorra (+376)</option>
                                            <option data-countryCode="AO" value="+244">Angola (+244)</option>
                                            <option data-countryCode="AI" value="+1264">Anguilla (+1264)</option>
                                            <option data-countryCode="AG" value="+1268">Antigua &amp; Barbuda (+1268)
                                            </option>
                                            <option data-countryCode="AR" value="+54">Argentina (+54)</option>
                                            <option data-countryCode="AM" value="+374">Armenia (+374)</option>
                                            <option data-countryCode="AW" value="+297">Aruba (+297)</option>
                                            <option data-countryCode="AU" value="+61">Australia (+61)</option>
                                            <option data-countryCode="YE" value="+969">Yemen (North)(+969)</option>
                                            <option data-countryCode="YE" value="+967">Yemen (South)(+967)</option>
                                            <option data-countryCode="ZM" value="+260">Zambia (+260)</option>
                                            <option data-countryCode="ZW" value="+263">Zimbabwe (+263)</option>
                                        </optgroup>
                                    </select>
                                </div> --}}
                                <div class="col-md-6">
                                    <input id="mobile_no" type="text"
                                        class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no"
                                        value="{{ old('mobile_no') }}" required autocomplete="mobile_no" autofocus
                                        placeholder="Enter Your Registered Mobile Number">

                                    @error('mobile_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Generate OTP') }}
                                    </button>

                                    @if (Route::has('login'))
                                        <a class="btn btn-link" href="{{ route('login') }}">
                                            {{ __('Login With Email') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
