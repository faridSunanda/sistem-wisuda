@extends('errors.layout')

@section('title', 'Forbidden')
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
