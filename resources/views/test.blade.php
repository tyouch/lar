@extends('layouts.app')
@section('title', 'test')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>123456 - [{{ $age }}]</p>
                <p>test/xxx (xxx >= 200 redirect to user)</p>
                <form action="" method="post">
                    {{ csrf_field() }}{{ method_field('PUT') }}
                </form>
            </div>
        </div>
    </div>
@endsection
