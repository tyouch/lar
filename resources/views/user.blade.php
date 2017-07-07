<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/26
 * Time: 15:02
 */
?>

@extends('layouts.app')
@section('title', 'myUser')

@section('content')
    <div class="container">
        <div class="row">
            @section('sidebar')
                <div class="col-md-3">
                    <div class="panel panel-success">
                        <div class="panel-heading">Navi</div>
                        <ul class="list-group">
                            <li class="list-group-item">nav11</li>
                            <li class="list-group-item">nav22</li>
                        </ul>
                    </div>
                </div>
            @show

            <div class="col-md-9">
                <div class="panel panel-info">
                    <div class="panel-heading">Content</div>
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <th>Username</th>
                                <th>Credit1</th>
                                <th>Credit2</th>
                            </tr>
                            @foreach ($members as $user)
                                <tr>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->credit1 }}</td>
                                    <td>{{ $user->credit2 }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="panel-footer">Copyritht</div>
                </div>
            </div>
        </div>
    </div>

@endsection