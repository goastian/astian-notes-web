@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card bg-dark text-light mt-5">
            <div class="card-head mb-5 text-center fw-bold h1 text-secondary">
                @if (isset($code))
                    <span>{{ $code }}</span>
                @endif
            </div>
            <div class="card-body text-center mt-5">
                <div class="mb-2">
                    @if (isset($message))
                        <span>{{ $message }}</span>
                    @endif
                </div>
                <div class="mt-5">
                    @if (isset($code) && $code == 401)
                        <form action="/login" method="GET">
                            <button type="submit" class="btn btn-primary">
                                Ir al login
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
